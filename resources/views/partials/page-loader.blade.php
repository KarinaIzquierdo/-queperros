<style>
    .mq-page-loader{
        position: fixed;
        inset: 0;
        z-index: 100000;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        opacity: 1;
        transition: opacity .22s ease;
        gap: 20px;
    }

    .mq-page-loader.mq-page-loader--hide{
        opacity: 0;
        pointer-events: none;
    }

    .mq-page-loader[hidden]{
        display: none !important;
    }

    .mq-page-loader img{
        width: clamp(640px, 70vw, 900px);
        height: auto;
        max-width: 100vw;
        image-rendering: auto;
    }

    .mq-loader-text {
        font-family: 'Chango', system-ui;
        font-size: 2.5rem;
        color: #4B2E1E;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 2px 2px 0px #fff;
        animation: mq-pulse 1.5s infinite ease-in-out;
        display: block;
    }

    @keyframes mq-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.05); }
    }
</style>

<div class="mq-page-loader" id="mq-page-loader" aria-label="Cargando">
    <img src="{{ asset('img/%2Bqperros.gif') }}" alt="Cargando..." />
    <div class="mq-loader-text">Cargando...</div>
</div>

<script>
    (function () {
        const loader = document.getElementById('mq-page-loader');
        if (!loader) return;

        const startedAt = (typeof performance !== 'undefined' && performance.now) ? performance.now() : Date.now();
        const MIN_VISIBLE_MS = 650;

        const hide = () => {
            loader.classList.add('mq-page-loader--hide');
            setTimeout(() => {
                loader.setAttribute('hidden', 'hidden');
            }, 260);
        };

        const hideSoon = () => {
            const now = (typeof performance !== 'undefined' && performance.now) ? performance.now() : Date.now();
            const elapsed = now - startedAt;
            const wait = Math.max(0, MIN_VISIBLE_MS - elapsed);
            setTimeout(hide, wait);
        };

        window.addEventListener('load', hideSoon, { once: true });
        window.addEventListener('pageshow', (e) => {
            if (e.persisted) hideSoon();
        });

        if (document.readyState === 'complete') {
            hideSoon();
        }

        setTimeout(hide, 8000);
    })();
</script>
