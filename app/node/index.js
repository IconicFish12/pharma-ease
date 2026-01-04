import dotenv from "dotenv";
import admin from "firebase-admin";
import service from "./firebase/pharma-ease-firebase-adminsdk-fbsvc-09a5298f61.json" with { type: "json" };
import Echo from 'laravel-echo';
import Pusher from "pusher-js";
import { WebSocket } from 'ws';
import fs from 'fs';
import path from 'path';

global.Pusher = Pusher;
global.WebSocket = WebSocket;

dotenv.config();

if (!admin.apps.length) {
    admin.initializeApp({
        credential: admin.credential.cert(service)
    });
}

let lowStockBuffer = [];
let expiredBuffer = [];

function writeLog(status, title, responseOrError) {
    const timestamp = new Date().toISOString();
    const logMessage = `[${timestamp}] [${status}] ${title} | Info: ${JSON.stringify(responseOrError)}\n`;

    fs.appendFile(path.join(process.cwd(), 'fcm_history.log'), logMessage, (err) => {
        if (err) console.error("Gagal menulis log lokal:", err);
    });
}

const echo = new Echo({
    broadcaster: 'reverb',
    key: process.env.VITE_REVERB_APP_KEY,
    wsHost: process.env.VITE_REVERB_HOST,
    wsPort: process.env.VITE_REVERB_PORT ?? 8080,
    wssPort: process.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (process.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
    Pusher: Pusher
});

console.log(`[Gatekeeper] Menghubungkan ke Reverb di ${process.env.VITE_REVERB_HOST}:${process.env.VITE_REVERB_PORT}...`);

echo.channel('inventory-channel')
    .listen('.low.stock', (e) => {
        console.log(`[Event Masuk] Low Stock: ${e.medicine_name}`);

        const exists = lowStockBuffer.find(item => item.medicine_id === e.medicine_id);
        if (!exists) {
            lowStockBuffer.push(e);
        }
    })
    .listen('.expired.meds', (e) => {
        console.log(`[Event Masuk] Expired Batch: ${e.count} items`);

        e.items.forEach(item => {
             expiredBuffer.push(item);
        });
    });


async function sendFCM(title, body, dataPayload = {}) {
    const message = {
        notification: { title, body },
        data: {
            ...dataPayload,
            click_action: 'FLUTTER_NOTIFICATION_CLICK'
        },
        topic: process.env.FCM_TOPIC_ADMIN
    };

    try {
        const response = await admin.messaging().send(message);
        console.log(`[FCM] Terkirim: ${title}`);

        writeLog('SUCCESS', title, response);
    } catch (error) {
        console.error('[FCM] Gagal:', error.message);

        writeLog('FAILED', title, error.message);
    }
}

const AGGREGATION_INTERVAL = 30000;

setInterval(() => {
    if (lowStockBuffer.length > 0) {
        const count = lowStockBuffer.length;
        const firstItem = lowStockBuffer[0].medicine_name;

        let title = "Peringatan Stok Menipis!";
        let body = count === 1
            ? `Stok obat ${firstItem} kritis.`
            : `${firstItem} dan ${count - 1} obat lainnya kritis.`;

        sendFCM(title, body, { type: 'low_stock_summary' });
        console.log(`[Summary] Low Stock (${count}) processed.`);
        lowStockBuffer = [];
    }

    if (expiredBuffer.length > 0) {
        const count = expiredBuffer.length;

        let title = "Peringatan Kadaluarsa!";
        let body = `${count} obat telah melewati tanggal kadaluarsa. Cek gudang segera.`;

        sendFCM(title, body, { type: 'expired_summary' });
        console.log(`[Summary] Expired (${count}) processed.`);
        expiredBuffer = [];
    }

}, AGGREGATION_INTERVAL);
