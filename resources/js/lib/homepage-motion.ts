import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/*
 * GSAP normally stretches an animation when frames are dropped, so a stall
 * makes everything play in slow motion afterwards. For an opening panel that
 * holds the page back, catching up is the kinder behaviour: a reader whose
 * phone hiccups gets to the content sooner rather than watching the intro
 * crawl. It is also the pairing GSAP documents for smooth-scroll libraries.
 */
gsap.ticker.lagSmoothing(0);

/**
 * Motion for the homepage, and the homepage only.
 *
 * Everything is opt-in through `data-*` attributes in the markup, so each
 * component declares how it should move without importing GSAP itself. Other
 * pages keep the lighter IntersectionObserver directive in `reveal.ts`.
 *
 * Headings are moved as whole elements, never split into words or letters.
 * The brand headings paint their gradient with `background-clip: text`, which
 * a child span does not inherit — split them and the words turn invisible —
 * and several carry ornaments positioned outside their own box, which any
 * clip-path reveal would cut in half.
 */

const EASE = 'power3.out';

/** How far down the viewport a section comes before it plays. */
const START = 'top 85%';

export function prefersReducedMotion(): boolean {
    return typeof window !== 'undefined' && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/**
 * Lenis runs its own rAF loop, so ScrollTrigger never hears about the scrolls
 * it performs. Without this, triggers fire late or not at all.
 */
export function bindSmoothScroll(lenis: { on: (event: string, handler: () => void) => void }): void {
    lenis.on('scroll', ScrollTrigger.update);
}

/**
 * The opening: a full-screen panel, "PEMIRA 2026" rising a letter at a time,
 * then the panel wiping upward to hand over the page.
 */
export function playIntro(overlay: HTMLElement, onReveal: () => void, onDone: () => void): gsap.core.Timeline {
    const chars = overlay.querySelectorAll<HTMLElement>('[data-intro-char]');

    return (
        gsap
            .timeline({ defaults: { ease: EASE }, onComplete: onDone })
            .set(overlay, { clipPath: 'inset(0% 0% 0% 0%)' })
            .from(chars, {
                // Past 100% so each letter clears its mask completely at rest;
                // anything less leaves a sliver showing through.
                yPercent: 120,
                opacity: 0,
                duration: 0.72,
                stagger: 0.05,
            })
            // The letters leave a beat ahead of the panel. That gap is what makes
            // the wipe read as a panel lifting rather than one flat sheet sliding.
            .to(chars, { yPercent: -130, opacity: 0, duration: 0.5, ease: 'power2.in', stagger: 0.025 }, '+=0.35')
            // The hero starts arriving as the panel lifts, not after it has
            // gone. Waiting until the end leaves a beat of blank white page,
            // which reads as the site having failed rather than as a reveal.
            .add(onReveal)
            .to(overlay, { clipPath: 'inset(0% 0% 100% 0%)', duration: 1, ease: 'power3.inOut' }, '-=0.3')
    );
}

/** The hero, played as the intro panel clears it. */
export function revealHero(root: ParentNode): gsap.core.Timeline {
    const timeline = gsap.timeline({ defaults: { ease: 'expo.out' } });

    const heading = root.querySelector<HTMLElement>('[data-hero="heading"]');
    const items = root.querySelectorAll<HTMLElement>('[data-hero="item"]');
    const card = root.querySelector<HTMLElement>('[data-hero="card"]');

    /*
     * fromTo, never from: gsap.from() treats the element's CURRENT value as
     * the destination, and these elements are pinned at opacity 0 by CSS until
     * JS arrives — so a plain `from` animated them from invisible to invisible.
     */
    if (heading) timeline.fromTo(heading, { y: 90, opacity: 0 }, { y: 0, opacity: 1, duration: 1.3 });
    if (items.length) timeline.fromTo(items, { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 1, stagger: 0.14 }, '-=0.95');
    if (card) timeline.fromTo(card, { y: 70, scale: 0.92, opacity: 0 }, { y: 0, scale: 1, opacity: 1, duration: 1.2 }, '-=1.05');

    return timeline;
}

/**
 * Wire every `data-anim`, `data-parallax` and `data-count` element to the
 * scroll.
 *
 * Returns a teardown: Inertia keeps the app alive between pages, and a
 * ScrollTrigger left behind would go on measuring a document that no longer
 * holds the element it watches.
 */
export function setupScrollAnimations(root: ParentNode): () => void {
    const context = gsap.context(() => {
        root.querySelectorAll<HTMLElement>('[data-anim]').forEach((element) => {
            const variant = element.dataset.anim || 'up';
            const delay = Number(element.dataset.animDelay ?? 0) / 1000;
            const scrollTrigger = { trigger: element, start: START, once: true };

            if (variant === 'stagger') {
                // The wrapper itself is only a container; its children carry
                // the motion, so it is shown outright.
                gsap.set(element, { opacity: 1 });

                gsap.fromTo(
                    Array.from(element.children),
                    { y: 52, opacity: 0 },
                    { y: 0, opacity: 1, duration: 0.9, ease: EASE, stagger: 0.12, delay, scrollTrigger },
                );

                return;
            }

            gsap.fromTo(element, fromVars(variant), {
                x: 0,
                y: 0,
                scale: 1,
                opacity: 1,
                duration: 1,
                ease: variant === 'heading' ? 'expo.out' : EASE,
                delay,
                scrollTrigger,
            });
        });

        // Ornaments drift against the scroll. Tied to progress rather than
        // played once, so the parallax tracks the reader's own pace.
        root.querySelectorAll<HTMLElement>('[data-parallax]').forEach((element) => {
            const distance = Number(element.dataset.parallax || 80);

            gsap.to(element, {
                y: -distance,
                ease: 'none',
                scrollTrigger: { trigger: element, start: 'top bottom', end: 'bottom top', scrub: true },
            });
        });

        // Counters run up to their printed value, which stays in the markup so
        // a reader without the animation still sees the real number.
        root.querySelectorAll<HTMLElement>('[data-count]').forEach((element) => {
            const target = Number(element.dataset.count || element.textContent || 0);
            const suffix = element.dataset.countSuffix ?? '';
            const counter = { value: 0 };

            gsap.to(counter, {
                value: target,
                duration: 1.4,
                ease: 'power2.out',
                scrollTrigger: { trigger: element, start: START, once: true },
                onUpdate: () => {
                    element.textContent = Math.round(counter.value) + suffix;
                },
            });
        });
    }, root as Element);

    ScrollTrigger.refresh();

    return () => context.revert();
}

/**
 * Show everything at once, for readers who asked not to be moved around.
 * Called instead of the animations, never alongside them.
 */
export function showEverything(root: ParentNode): void {
    root.querySelectorAll<HTMLElement>('[data-anim], [data-hero]').forEach((element) => {
        gsap.set(element, { clearProps: 'transform', opacity: 1 });
    });
}

function fromVars(variant: string): gsap.TweenVars {
    switch (variant) {
        case 'left':
            return { x: -64, opacity: 0 };
        case 'right':
            return { x: 64, opacity: 0 };
        case 'zoom':
            return { scale: 0.9, opacity: 0 };
        case 'heading':
            return { y: 72, opacity: 0 };
        case 'up':
        default:
            return { y: 52, opacity: 0 };
    }
}
