<?php
// statusbalk afdrukken als er een waarde wordt meegegeven aan de pagina
if (isset($statusbalk)): ?>
    <style>
        .statusBalk {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100vw;
            text-align: center;
            background-color: var(--rood);
            color: var(--geel);
            padding: 0.5rem;
            letter-spacing: 2px;
            z-index: 1000;
            /* Eerst schuift de balk in, dan vervaagt hij */
            animation: slideIn 1s ease-out forwards, fadeOut 2s 4s forwards;
            pointer-events: none;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-100%);
            }

            to {
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }
    </style>
    <div class="statusBalk"><?= $statusbalk ?></div>
<?php endif; ?>