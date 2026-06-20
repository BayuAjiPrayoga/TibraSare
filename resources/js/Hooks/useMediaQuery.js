import { useState, useEffect } from 'react';
import { BREAKPOINTS } from '@/Lib/constants';

/**
 * Hook to detect current viewport breakpoint.
 * @param {string} query - e.g. '(min-width: 768px)' or use breakpoint name 'md', 'lg'
 * @returns {boolean}
 */
export default function useMediaQuery(query) {
    // Support shorthand breakpoint names
    const resolvedQuery = (() => {
        if (query === 'sm') return `(min-width: ${BREAKPOINTS.SM}px)`;
        if (query === 'md') return `(min-width: ${BREAKPOINTS.MD}px)`;
        if (query === 'lg') return `(min-width: ${BREAKPOINTS.LG}px)`;
        if (query === 'xl') return `(min-width: ${BREAKPOINTS.XL}px)`;
        return query;
    })();

    const [matches, setMatches] = useState(() => {
        if (typeof window !== 'undefined') {
            return window.matchMedia(resolvedQuery).matches;
        }
        return false;
    });

    useEffect(() => {
        if (typeof window === 'undefined') return;

        const mediaQuery = window.matchMedia(resolvedQuery);
        const handler = (e) => setMatches(e.matches);

        mediaQuery.addEventListener('change', handler);
        setMatches(mediaQuery.matches);

        return () => mediaQuery.removeEventListener('change', handler);
    }, [resolvedQuery]);

    return matches;
}
