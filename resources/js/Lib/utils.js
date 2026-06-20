import clsx from 'clsx';
import { twMerge } from 'tailwind-merge';

/**
 * Merge Tailwind classes with conflict resolution.
 * Usage: cn('px-4 py-2', conditional && 'bg-primary', className)
 */
export function cn(...inputs) {
    return twMerge(clsx(inputs));
}

/**
 * Format number as Indonesian Rupiah currency.
 * @param {number} amount
 * @returns {string} e.g. "Rp 450.000"
 */
export function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

/**
 * Format date to Indonesian locale.
 * @param {string|Date} date
 * @param {object} options - Intl.DateTimeFormat options
 * @returns {string} e.g. "17 Juni 2026"
 */
export function formatDate(date, options = {}) {
    const defaults = {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    };
    return new Intl.DateTimeFormat('id-ID', { ...defaults, ...options }).format(
        new Date(date)
    );
}

/**
 * Format date as short format.
 * @param {string|Date} date
 * @returns {string} e.g. "17 Jun 2026"
 */
export function formatDateShort(date) {
    return formatDate(date, { month: 'short' });
}

/**
 * Calculate number of nights between two dates.
 * @param {string|Date} checkIn
 * @param {string|Date} checkOut
 * @returns {number}
 */
export function calculateNights(checkIn, checkOut) {
    const msPerDay = 1000 * 60 * 60 * 24;
    const start = new Date(checkIn);
    const end = new Date(checkOut);
    return Math.max(1, Math.round((end - start) / msPerDay));
}

/**
 * Get initials from a full name for avatar fallback.
 * @param {string} name
 * @returns {string} e.g. "AF" for "Ahmad Fauzi"
 */
export function getInitials(name) {
    if (!name) return '?';
    return name
        .split(' ')
        .map((word) => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
}

/**
 * Debounce a function.
 * @param {Function} fn
 * @param {number} delay - ms
 * @returns {Function}
 */
export function debounce(fn, delay = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}
