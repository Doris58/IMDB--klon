<?php require_once __DIR__ . '/_header.php'; ?>

<div id = "registration-form-container">
    <form method = "post" action = "index.php?rt=registerMe">
    
    <input type = "text" name = "ime" placeholder = "Ime"/>

    <br/> <br/>

    <input type = "text" name = "prezime" placeholder = "Prezime"/>

    <br/> <br/>

    <input type = "text" name = "email" placeholder = "Email"/>

    <br/> <br/>

    <input type = "text" name = "username" placeholder = "Username"/>

    <br/> <br/>

    <input type = "password" name = "password" placeholder = "Password"/>

    <br/> 
        <?php
            if($poruka !== '')
                echo '<p>' . $poruka . '</p>';
        ?>
    <br/>

    <input type = "submit" name = "btn_registration" value = "Registriraj se"/>
    </form>
</div>

<style>   
    body
    {
        display: flex;
        flex-direction: column;
        align-items: center;
        /* justify-content: center; */
        min-height: 100vh;
        margin: 0;
    }
</style>

<?php require_once __DIR__ . '/_footer.php'; ?>