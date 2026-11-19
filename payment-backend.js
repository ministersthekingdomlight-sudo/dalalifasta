import { createClient } from '@supabase/supabase-js';

// Usanidi wa Supabase (Weka URL na Anon Key yako hapa)
const supabaseUrl = 'YOUR_SUPABASE_URL';
const supabaseKey = 'YOUR_SUPABASE_ANON_KEY';
const supabase = createClient(supabaseUrl, supabaseKey);

// Orodha ya API Keys za Wafanyabiashara kwa kila nchi
const merchantCredentials = {
    KE: {
        consumer_key: 'qkio1BGGYAXTu2JOfm7XSXNruoZsrqEW',
        consumer_secret: 'osGQ364R49cXKeOYSpaOnT++rHs='
    },
    UG: {
        consumer_key: 'TDpigBOOhs+zAl8cwH2Fl82jJGyD8xev',
        consumer_secret: '1KpqkfsMaihIcOlhnBo/gBZ5smw='
    },
    TZ: {
        consumer_key: 'ngW+UEcnDhltUc5fxPfrCD987xMh3Lx8',
        consumer_secret: 'q27RChYs5UkypdcNYKzuUw460Dg='
    },
    MW: {
        consumer_key: 'htMsEFfIVHfhqBL9O0ykz8wuedfFyg1s',
        consumer_secret: 'DcwkVNIiyijNWn1fdL/pa4K6khc='
    },
    RW: {
        consumer_key: 'wCGzX1fNzvtI5lMR5M4AxvxBmLpFgZzp',
        consumer_secret: 'uU7R9g2IHn9dkrKDVIfcPppktIo='
    },
    ZM: {
        consumer_key: 'v988cq7bMB6AjktYo/drFpe6k2r/y7z3',
        consumer_secret: '3p0F/KcY8WAi36LntpPf/Ss0MhQ='
    },
    ZW: {
        consumer_key: 'vknEWEEFeygxAX+C9TPOhvkbkPsj8qXK',
        consumer_secret: 'MOOP31smKijvusQbNXn/s7m8jsql'
    }
};

// Kazi ya kushughulikia malipo na mgawanyo
async function processRentalPayment(paymentData) {
    const { countryCode, landlordId, tenantName, totalPaid, paymentMethod } = paymentData;

    // 1. Pata API Keys za nchi husika
    const merchant = merchantCredentials[countryCode];
    if (!merchant) {
        throw new Error("Nchi haitambuliki au haina Merchant Keys zilizowekwa.");
    }

    console.log(`Inatumia Merchant Keys za ${countryCode} (Key: ${merchant.consumer_key.substring(0, 6)}...)`);

    // 2. Hesabu mgawanyo wa pesa (Mfano: Kamisheni ya App ni 10% au mwezi 1)
    const appCommission = totalPaid * 0.10; 
    const landlordAmount = totalPaid - appCommission;

    // 3. Hifadhi taarifa za malipo kwenye Supabase
    const { data, error } = await supabase
        .from('transactions')
        .insert([
            {
                landlord_id: landlordId,
                tenant_name: tenantName,
                total_paid: totalPaid,
                app_commission: appCommission,
                landlord_amount: landlordAmount,
                payment_method: paymentMethod,
                country_code: countryCode,
                status: 'completed'
            }
        ]);

    if (error) {
        console.error("Hitilafu wakati wa kuhifadhi Supabase:", error.message);
        return { success: false, error: error.message };
    }

    console.log("Malipo yamefanikiwa na kuhifadhiwa kwenye Supabase!");
    return { success: true, data, landlordAmount, appCommission };
}