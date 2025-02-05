<?php

//statusbalk afdrukken als er een waarde wordt meegegeven aan de pagina
if (isset($statusbalk)): ?>
    <style>
        .statusBalk {
            position: absolute;
            top: -5rem;
            left: 0;
            width: 100vw;
            text-align: center;
            background-color: var(--rood);
            color: var(--geel);
            padding-top: .5rem;
            padding-bottom: .5rem;
            letter-spacing: 2px;
            animation-name: showStatusBalk;
            animation-duration: 2s;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;
            /* Zorgt ervoor dat de eindpositie blijft staan */
            animation-timing-function: ease-in-out;
        }

        @keyframes showStatusBalk {
            0% {
                transform: translateY(0rem)
            }

            100% {
                transform: translateY(10rem)
            }
        }
    </style>


    <div class='statusBalk'><?= $statusbalk ?></div>
<?php endif;
