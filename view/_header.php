<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMDB klon</title>
    <link rel="icon" type="image/x-icon" href="uploads/medo.png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', sans-serif;
            background-color: #03152e; /* Crna pozadina */
            color: #ffffff; /* Bijeli tekst */
        }

        h1 {
            font-family: 'Georgia', sans-serif;
            font-weight: 700; /* bold */
            color: #ffffff; /* Bijeli tekst */
        }

        button {
            font-family: 'Georgia', sans-serif;
            background-color: #2498c9; 
            border: none;
            color: white;
            padding: 10px 20px; /* Smanjili smo padding */
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px; /* Smanjili smo font-size */
            margin: 2px 1px; /* Smanjili smo margin */
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2482c9;
        }

        button:active {
            background-color: #2469c9;
        }

        a {
            color: #ffffff; /* Bijeli tekst za linkove */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        input, select {
            background-color: #03152e; /* Crna pozadina */
            color: #ffffff; /* Bijeli tekst */
            border: 1px solid #ffffff; /* Bijela granica */
            padding: 5px;
        }

        .istakni {
        border: 2px solid #e4edf0; /* Promijenite ovo prema želji */
    }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
</head>
<body>