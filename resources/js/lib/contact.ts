/**
 * The one place the PEMIRA admin contact lives. Both the failed-login dialog
 * and the "not on the roll" card on the dashboard point at it, and a committee
 * handover only has to change the number here.
 */
const ADMIN_WHATSAPP_NUMBER = '62895622771393';

export function adminWhatsappUrl(message: string): string {
    return `https://wa.me/${ADMIN_WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`;
}

export const CONTACT_ADMIN_ACCOUNT_URL = adminWhatsappUrl('Halo kak, bisa tolong dibantu untuk aktivasi akun PEMIRA saya?');

export const CONTACT_ADMIN_VOTER_URL = adminWhatsappUrl('Halo kak, saya belum terdaftar sebagai pemilih di PEMIRA. Bisa tolong dibantu?');
