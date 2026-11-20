import type { Directive, DirectiveBinding } from 'vue';

/**
 * Scroll reveal, as a directive so markup only carries intent:
 *
 *   <div v-reveal>              fade and rise
 *   <div v-reveal.clip>         wipe open from the left
 *   <div v-reveal.left="200">   slide in from the left, 200ms later
 *
 * One shared IntersectionObserver drives every element, each is unobserved
 * once it has played, and the whole thing is skipped for visitors who asked
 * for reduced motion.
 */
type RevealBinding = DirectiveBinding<number | undefined>;

const BASE_CLASS = 'reveal';

const VARIANTS = ['up', 'left', 'right', 'zoom', 'clip', 'clipUp'] as const;

const VARIANT_CLASS: Record<(typeof VARIANTS)[number], string> = {
    up: 'reveal-up',
    left: 'reveal-left',
    right: 'reveal-right',
    zoom: 'reveal-zoom',
    clip: 'reveal-clip',
    clipUp: 'reveal-clip-up',
};

function classesFor(binding: RevealBinding): string[] {
    const variant = VARIANTS.find((name) => binding.modifiers[name]);

    return [BASE_CLASS, VARIANT_CLASS[variant ?? 'up']];
}

/** `v-reveal="150"` or `v-reveal.delay-150` style stagger, in milliseconds. */
function delayFor(binding: RevealBinding): number {
    if (typeof binding.value === 'number' && Number.isFinite(binding.value)) {
        return Math.max(0, Math.min(binding.value, 2000));
    }

    return 0;
}

function prefersReducedMotion(): boolean {
    return typeof window !== 'undefined' && window.matchMedia?.('(prefers-reduced-motion: reduce)').matches === true;
}

let observer: IntersectionObserver | null = null;

function getObserver(): IntersectionObserver | null {
    if (typeof window === 'undefined' || !('IntersectionObserver' in window)) {
        return null;
    }

    observer ??= new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (!entry.isIntersecting) {
                    continue;
                }

                entry.target.classList.add('is-revealed');
                observer?.unobserve(entry.target);
            }
        },
        {
            // Start a touch before the element is fully on screen, so the
            // motion finishes about when the reader gets there.
            rootMargin: '0px 0px -8% 0px',
            threshold: 0.12,
        },
    );

    return observer;
}

export const vReveal: Directive<HTMLElement, number | undefined> = {
    /**
     * With SSR the hidden state has to be in the served HTML, otherwise the
     * content paints first and only then jumps back to hide itself.
     */
    getSSRProps(binding) {
        const delay = delayFor(binding);

        return {
            class: classesFor(binding).join(' '),
            style: delay > 0 ? { '--reveal-delay': `${delay}ms` } : undefined,
        };
    },

    mounted(el, binding) {
        el.classList.add(...classesFor(binding));

        const delay = delayFor(binding);

        if (delay > 0) {
            el.style.setProperty('--reveal-delay', `${delay}ms`);
        }

        const target = getObserver();

        // No observer, or motion is unwelcome: show it and move on.
        if (!target || prefersReducedMotion()) {
            el.classList.add('is-revealed');

            return;
        }

        target.observe(el);
    },

    unmounted(el) {
        observer?.unobserve(el);
    },
};
