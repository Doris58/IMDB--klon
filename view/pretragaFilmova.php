<div>
    <button name="naslovna" type="button" id=naslovna-button>Naslovna</button>
    <button name="profil" type="button" id="profil-button">Profil</button>
    <button name="odjava" type="button" id="odjava-button">Odjava</button>
    <br/> <br/>
    <form method="post" action="index.php?rt=searchMovies">
        <select name="filter">
            <option value="svi" selected>Film</option>
            <option value="zanr">Zanr</option>
            <option value="glumac">Glumac</option>
            <option value="godina">Godina</option>
            <option value="redatelj">Redatelj</option>
        </select>
        <input type="text" name="naziv" placeholder="Pretrazi film">
        <input type="submit" name="pretrazi" />
    </form>
</div>

<script>
    document.getElementById('profil-button').addEventListener('click', function() {
        window.location.href = 'index.php?rt=korisnickiProfil';
    });

    document.getElementById('naslovna-button').addEventListener('click', function() {
        window.location.href = 'index.php?rt=korisnikNaslovna';
    });

    document.getElementById('odjava-button').addEventListener('click', function() {
        window.location.href = 'index.php?rt=odjava';
    });
</script>

</body>
</html>