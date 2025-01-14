<?php
$title = "HP-305 comfort hoofdtelefoon";
include dirname(__DIR__, 2) . "/incs/top.php";
?>
<link rel="stylesheet" href="prod.css">
<style>
    .grid-container {
        padding: 4rem;
        display: grid;
        grid-template-areas:
            "een een een een twee twee"
            "drie drie vier vier vijf vijf"
            "zes zes zes zeven zeven zeven";
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 2fr 1fr 2fr;
        height: 100vh;
        gap: 20px;
    }

    .grid-container>div {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        color: white;
        border-radius: 12px;
    }

    .een {
        grid-area: een;
        background-color: #2E5266;
    }

    .twee {
        grid-area: twee;
        background-color: #6E8898;
    }

    .drie {
        grid-area: drie;
        background-color: #879DAA;
    }

    .vier {
        grid-area: vier;
        background-color: #93A7B3;
    }

    .vijf {
        grid-area: vijf;
        background-color: #9FB1BC;
    }

    .zes {
        grid-area: zes;
        background-color: #D3D0CB;
    }

    .zeven {
        grid-area: zeven;
        background-color: #E2C044;
    }
</style>

<body class='grid-container'>
    <div class="een">een</div>
    <div class="twee">twee</div>
    <div class="drie">drie</div>
    <div class="vier">vier</div>
    <div class="vijf">vijf</div>
    <div class="zes">zes</div>
    <div class="zeven">zeven</div>



</body>
<?php
include dirname(__DIR__, 2) . "/incs/bottom.php";
