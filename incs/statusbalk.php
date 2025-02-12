<?php
// statusbalk afdrukken als er een waarde wordt meegegeven aan de pagina
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
            /* Combineert de slide-in animatie met een fade-out na 60 seconden */
            animation: showStatusBalk 2s ease-in-out forwards, fadeOutStatusBalk 2s 5s forwards;
        }

        @keyframes showStatusBalk {
            0% {
                transform: translateY(0rem);
            }

            100% {
                transform: translateY(10rem);
            }
        }

        @keyframes fadeOutStatusBalk {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }
    </style>

    <div class='statusBalk'><?= $statusbalk ?></div>
<?php endif; ?>