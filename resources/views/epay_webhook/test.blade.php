<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Epay Webhook Tester</title>

    <style>
        .align {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .grid {
            margin-left: auto;
            margin-right: auto;
            max-width: 320px;
            max-width: 20rem;
            width: 90%;
        }

        .hidden {
            border: 0;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }

        .icons {
            display: none;
        }

        .icon {
            display: inline-block;
            fill: #606468;
            font-size: 16px;
            font-size: 1rem;
            height: 1em;
            vertical-align: middle;
            width: 1em;
        }

        * {
            -webkit-box-sizing: inherit;
            box-sizing: inherit;
        }

        html {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            font-size: 100%;
            height: 100%;
        }

        body {
            background-color: #2c3338;
            color: #606468;
            font-family: 'Open Sans', sans-serif;
            font-size: 14px;
            font-size: 0.875rem;
            font-weight: 400;
            height: 100%;
            line-height: 1.5;
            margin: 0;
            min-height: 100vh;
        }

        a {
            color: #eee;
            outline: 0;
            text-decoration: none;
        }

        a:focus,
        a:hover {
            text-decoration: underline;
        }

        input {
            background-image: none;
            border: 0;
            color: inherit;
            font: inherit;
            margin: 0;
            outline: 0;
            padding: 0;
            -webkit-transition: background-color 0.3s;
            transition: background-color 0.3s;
        }

        input[type='submit'] {
            cursor: pointer;
        }

        .form {
            margin: -14px;
            margin: -0.875rem;
        }

        .form input[type='text'],
        .form input[type='submit'] {
            width: 100%;
        }

        .form__field {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin: 14px;
            margin: 0.875rem;
        }

        .form__input {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }


        .mainForm {
            color: #eee;
        }

        .mainForm label,
        .mainForm input[type='text'],
        .mainForm input[type='submit'] {
            border-radius: 0.25rem;
            padding: 16px;
            padding: 1rem;
        }

        .mainForm label {
            background-color: #363b41;
            border-bottom-right-radius: 0;
            border-top-right-radius: 0;
            padding-left: 20px;
            padding-left: 1.25rem;
            padding-right: 20px;
            padding-right: 1.25rem;
        }

        .mainForm input[type='text'] {
            background-color: #3b4148
        }

        .mainForm input[type='text']:focus,
        .mainForm input[type='text']:hover {
            background-color: #434a52;
        }

        .payBtn {
            background-color: #2ecc71;
            color: #eee;
            font-weight: 700;
            text-transform: uppercase;
        }

        .payBtn:focus,
        .payBtn:hover {
            background-color: #27ae60;
        }

        .success {
            background-color: #27ae60;
            padding: 10px;
            color: #fff;
            border-radius: 0.25rem;
            margin-bottom: 1.8rem;
            word-break: break-word;
        }
    </style>
</head>

<body class="align">
    <div class="grid">

        @if (session('paid_invoice'))
        <div class='success'>
            <h4>Successfully simulated payment of invoice number: {{ session('paid_invoice') }}</h4>
        </div>
        @endif

        <form action="{{ route('epay_webhook_tester.post') }}" method="post" class="form mainForm">
            @csrf
            <div class="form__field">
                <input type="text" name="invoice_id" class="form__input" placeholder="Invoice ID"
                    value="{{ old('invoice_id') }}" required>
            </div>
            <div class="form__field">
                <input type="submit" class="payBtn" value="Pay">
            </div>
        </form>
    </div>
</body>

</html>