<?php require_once __DIR__ . '/_header.php'; ?>

<div id="login-form-container">
    <form method="post" action="index.php?rt=login">
        <input type="text" name="username" placeholder="Username"/>
        <br/><br/>
        <input type="password" name="password" placeholder="Password"/>
        <br/>
        <?php
            if($poruka !== '')
                echo '<p>' . $poruka . '</p>';
        ?>
        <br/>
        <input type="submit" name="btn_user_log_in" value="Prijavi se"/>
        <br/><br/>
        <input type="submit" name="btn_admin_log_in" value="Prijavi se - administrator"/>
        <br/><br/>
        Nemaš račun? Registriraj se! <br/>
        <input type="submit" name="btn_registration" value="Registriraj se"/>
    </form>
</div>

<div id="canvas-container">
    <canvas height="600" width="1000" id="canvas"></canvas>
</div>

<script>
$(document).ready(crtaj);
function crtaj() {
    let ctx = $('#canvas').get(0).getContext('2d');

    // Postavljanje stila i debljine linije
    ctx.strokeStyle = 'rgb(59, 32, 4)';
    ctx.lineWidth = 8;

    // Početak crtanja
    ctx.beginPath();

    // Lice (veći krug)
    let faceX = 300; // X koordinate središta lica
    let faceY = 300; // Y koordinate središta lica
    let faceRadius = 100; // Polumjer lica
    ctx.arc(faceX, faceY, faceRadius, 0, 2 * Math.PI); // Cijeli krug za lice

    // Desna uha (manji polukrug)
    let earRadius = 50; // Polumjer ušiju
    let rightEarX = faceX + faceRadius / 2; // X koordinate desnog uha
    let rightEarY = faceY - faceRadius; // Y koordinate desnog uha
    ctx.moveTo(rightEarX + earRadius, rightEarY);
    ctx.arc(rightEarX, rightEarY, earRadius, 0, 2 * Math.PI); // Cijeli krug za desno uho

    // Lijeva uha (manji polukrug)
    let leftEarX = faceX - faceRadius / 2; // X koordinate lijevog uha
    let leftEarY = faceY - faceRadius; // Y koordinate lijevog uha
    ctx.moveTo(leftEarX + earRadius, leftEarY);
    ctx.arc(leftEarX, leftEarY, earRadius, 0, 2 * Math.PI); // Cijeli krug za lijevo uho

    // Završiti crtanje
    ctx.stroke();

    // Tekst
    ctx.font = '72px Arial';
    ctx.fillText('IMDBKlon', 500, 300);
}
</script>

<style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
    }
    
    #login-form-container {
        margin-bottom: 20px;
    }

    #canvas-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 600px;
        width: 100%;
    }

    canvas {
        border: 1px solid #000;
    }
</style>

<?php require_once __DIR__ . '/_footer.php'; ?>
