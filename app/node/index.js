import dotenv from "dotenv";
import { createClient } from "redis";
import admin from "firebase-admin";
import service from "./firebase/firebase-service-account.json";

dotenv.config();

admin.initializeApp({
    credential: admin.credential.cert(service)
});

let lowStockBuffer = [];
let expiredBuffer = [];


async function sendFCM(title, body, dataPayload = {}) {
    const message = {
        notification: {
            title: title,
            body: body
        },
        data: {
            ...dataPayload,
            click_action: 'FLUTTER_NOTIFICATION_CLICK'
        },
        topic: process.env.FCM_TOPIC_ADMIN
    };

    try {
        const response = await admin.messaging().send(message);
        console.log(`[FCM] Sukses kirim notif: ${response}`);
    } catch (error) {
        console.error('[FCM] Gagal kirim:', error);
    }
}

const AGGREGATION_INTERVAL = 30000;

setInterval(() => {
    if (lowStockBuffer.length > 0) {
        const count = lowStockBuffer.length;
        const firstItem = lowStockBuffer[0].medicine_name;

        let title = "Peringatan Stok Menipis!";
        let body = "";

        if (count === 1) {
            body = `Stok obat ${firstItem} tersisa sedikit. Segera restock.`;
        } else {
            body = `${firstItem} dan ${count - 1} obat lainnya mengalami stok kritis.`;
        }

        console.log(`[Gatekeeper] Mengirim ringkasan Low Stock (${count} item)...`);
        sendFCM(title, body, { type: 'low_stock_summary' });

        lowStockBuffer = [];
    }

    if (expiredBuffer.length > 0) {
        const count = expiredBuffer.length;

        let title = "Peringatan Kadaluarsa!";
        let body = `${count} obat telah melewati tanggal kadaluarsa. Harap periksa gudang.`;

        console.log(`[Gatekeeper] Mengirim ringkasan Expired (${count} item)...`);
        sendFCM(title, body, { type: 'expired_summary' });

        expiredBuffer = [];
    }

}, AGGREGATION_INTERVAL);


async function startRedisListener() {
    const client = createClient({
        url: process.env.REDIS_URL
    });

    client.on('error', (err) => console.error('[Redis] Error:', err));

    await client.connect();
    console.log('[Redis] Terhubung. Menunggu data dari Laravel...');

    await client.subscribe(process.env.REDIS_CHANNEL, (message) => {
        try {
            const data = JSON.parse(message);
            console.log(`[Masuk] Data diterima: ${data.type} - ${data.medicine_name}`);

            if (data.type === 'low_stock') {
                const exists = lowStockBuffer.find(item => item.medicine_id === data.medicine_id);
                if (!exists) {
                    lowStockBuffer.push(data);
                }
            }
            else if (data.type === 'expired') {
                expiredBuffer.push(data);
            }

        } catch (e) {
            console.error('[System] Error parsing JSON dari Laravel', e);
        }
    });
}

startRedisListener();
