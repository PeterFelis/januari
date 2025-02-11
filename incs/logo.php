<?php
// file: incs/logo.php
// toont logo incl eigen css
// geef $logo de waarde '.logo' of '.logoklein' mee
// $kleur = "wit" wit laden, anders standaard kleur
?>

<div class="<?= $logo ?>">
    <a href="/">
        <?php if ($kleur == "wit") echo '<img src="/afbeeldingen/fetumlogoWIT.png" alt="Fetum logo" />';
        else
            echo '<img src="/afbeeldingen/fetumlogo.png" alt="Fetum logo" />'; ?>
    </a>
</div>


<style>
    .logo {
        height: auto;
        width: 20vw;
        position: absolute;
        left: 5rem;
        top: 1rem;
        z-index: 10;
    }

    .logoklein {
        height: 80%;
        width: auto;
        position: absolute;
        left: 10rem;
        top: 1rem;
        z-index: 10;
    }

    .logo img {
        width: 100%;
        object-fit: contain;
        display: block;
    }


    .logoklein img {
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .logo::after {
        content: '';
        display: block;
        width: 120%;
        height: 250%;
        background: var(--accent);
        position: absolute;
        z-index: -1;
        top: -100%;
        left: -10%;
        transform: rotate(-15deg);
        border-radius: 0% 0% 20% 20%;
    }




    /*  10-02-2025 bleek niet nodig te zijn  -- nog weghalen?? gaf extra blok voor witte blok van logo
    .logo::before {
        content: '';
        display: block;
        width: 25vw;
        height: 10vh;
        background: var(--accent);
        position: absolute;
        z-index: -1;
        top: -4rem;
        left: -7rem;
        transform: rotate(-5deg);
        border-radius: 0% 0% 20% 20%;
    }
    */
</style>