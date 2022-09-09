<div class="white-popup-block zoom-anim-dialog">
    @if ($title)
        <h3>{{ $title }}</h3>
        @if ($description)
            <p class="text-secondary">{{ $description }}</p>
        @endif
    @endif

    @yield('content')
</div>

<style>
    .white-popup-block {
        position: relative;
        background: #FFF;
        padding: 2rem;
        width: auto;
        max-width: {{ $max_width ?? 600 }}px;
        margin: 1rem auto;
    }

    /**
       * Fade-zoom animation for first dialog
       */

    /* start state */
    .my-mfp-zoom-in .zoom-anim-dialog {
        opacity: 0;

        -webkit-transition: all 0.2s ease-in-out;
        -moz-transition: all 0.2s ease-in-out;
        -o-transition: all 0.2s ease-in-out;
        transition: all 0.2s ease-in-out;



        -webkit-transform: scale(0.8);
        -moz-transform: scale(0.8);
        -ms-transform: scale(0.8);
        -o-transform: scale(0.8);
        transform: scale(0.8);
    }

    /* animate in */
    .my-mfp-zoom-in.mfp-ready .zoom-anim-dialog {
        opacity: 1;

        -webkit-transform: scale(1);
        -moz-transform: scale(1);
        -ms-transform: scale(1);
        -o-transform: scale(1);
        transform: scale(1);
    }

    /* animate out */
    .my-mfp-zoom-in.mfp-removing .zoom-anim-dialog {
        -webkit-transform: scale(0.8);
        -moz-transform: scale(0.8);
        -ms-transform: scale(0.8);
        -o-transform: scale(0.8);
        transform: scale(0.8);

        opacity: 0;
    }

    /* Dark overlay, start state */
    .my-mfp-zoom-in.mfp-bg {
        opacity: 0;
        -webkit-transition: opacity 0.3s ease-out;
        -moz-transition: opacity 0.3s ease-out;
        -o-transition: opacity 0.3s ease-out;
        transition: opacity 0.3s ease-out;
    }
    /* animate in */
    .my-mfp-zoom-in.mfp-ready.mfp-bg {
        opacity: 0.8;
    }
    /* animate out */
    .my-mfp-zoom-in.mfp-removing.mfp-bg {
        opacity: 0;
    }
</style>


