<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GW ENT Beat Pricing</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @if ($setting && $setting->favicon_url)
        <link rel="icon" type="image/png" href="{{ $setting->favicon_url }}">
    @endif
    @if ($setting && $setting->favicon_url)
        <link rel="apple-touch-icon" href="{{ $setting->favicon_url }}">
    @endif
    @if ($setting && $setting->favicon_url)
        <meta name="msapplication-TileImage" content="{{ $setting->favicon_url }}">
    @endif
    @if ($setting && $setting->favicon_url)
        <link rel="shortcut icon" href="{{ $setting->favicon_url }}">
    @endif
    <meta property="og:title" content="GW ENT Beat Pricing">
    <meta property="og:image" content="{{ asset('storage/images/eyUeDXi0xqzpboxlMQcdECQtZfMARAd8VcSTw8xV.jpg') }}">
    <meta property="og:description"
        content=" A rhythm reference refers to the ability to create a custom beat based on the artist's rhythm
                        input or rhythmic arrangement.
                        This ensures the beat aligns with the artist's unique musical style and timing requirements. A chorus reference refers to the process of developing a beat that incorporates the artist's
                        provided vocal ideas or chorus arrangement.
                        This approach allows for the seamless integration of the artist's vocal concepts into the final
                        beat composition.">
    <meta property="og:url" content="https://www.gw-ent.co.za/beat-pricing" />
    <meta name="keywords" content="Genius Works beats Pricing, Beats, Lesotho Beats, GOG beats">
    @livewireStyles
    <style>
        /* styles.css */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #181e2e, #606e6f);
            color: white;
        }

        .pricing-table {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .title {
            text-align: center;
            font-size: 24px;
            color: #eb7c2f;
        }

        .subtitle {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
            color: #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        thead {
            background-color: #3a5d39;
        }

        thead th {
            color: white;
            padding: 10px;
            text-align: left;
        }

        tbody tr:nth-child(odd) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        tbody td {
            padding: 10px;
        }

        tbody td:first-child {
            font-weight: bold;
        }

        tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        @media screen and (max-width: 768px) {
            .pricing-table {
                padding: 15px;
            }

            table {
                font-size: 12px;
            }

            thead th,
            tbody td {
                padding: 8px;
            }
        }

        i {
            margin-right: 5px;
            color: #eb7c2f;
        }

        /* Info Section Styles */
        .info-section {
            background: linear-gradient(to bottom, #181e2e, #606e6f);
            color: #ffffff;
            padding: 20px;
            margin: 20px auto;
            border-radius: 8px;
            max-width: 800px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .info-title {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #eb7c2f;
        }

        .info-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .info-box {
            padding: 15px;
            background: #303a45;
            border-left: 4px solid #eb7c2f;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .info-box h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #74afa0;
        }

        .info-box p {
            font-size: 16px;
            line-height: 1.5;
            color: #c4c8cb;
        }

        .info-box i {
            margin-right: 8px;
            color: #eb7c2f;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background: linear-gradient(to bottom, #181e2e, #606e6f);
            color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            margin: 10% auto;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .close {
            color: #eb7c2f;
            font-size: 28px;
            position: absolute;
            right: 15px;
            top: 10px;
            cursor: pointer;
        }

        .modal h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 15px;
            color: #eb7c2f;
        }

        .modal-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .modal-box {
            padding: 15px;
            background: #303a45;
            border-left: 4px solid #eb7c2f;
            border-radius: 5px;
        }

        .modal-box h3 {
            color: #74afa0;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .modal-box p {
            font-size: 16px;
            line-height: 1.5;
            color: #c4c8cb;
        }

        .modal-box i {
            margin-right: 8px;
            color: #eb7c2f;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            padding: 2px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h4 {
            text-align: center;
            margin-bottom: 20px;

        }

        td input,
        td select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        td input:focus,
        td select:focus {
            border-color: #eb7c2f;
            outline: none;
        }

        button {
            background-color: #eb7c2f;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }


        /* CSS */
        .button-51 {
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
            box-sizing: border-box;
            font-size: .8125rem;
            font-weight: 450;
            letter-spacing: normal;
            -webkit-tap-highlight-color: transparent;
            align-items: center;
            background: #fff;
            border-radius: .5rem;
            border: 0;
            box-shadow: inset 0 -0.0625rem 0 #b5b5b5, inset -0.0625rem 0 0 #e3e3e3, inset 0.0625rem 0 0 #e3e3e3, inset 0 0.0625rem 0 #e3e3e3;
            color: #303030;
            cursor: pointer;
            display: inline-flex;
            justify-content: center;
            line-height: 1;
            margin: 0;
            min-height: 1.75rem;
            min-width: 1.75rem;
            padding: .375rem .75rem;
            position: relative;
            text-align: center;
            text-decoration: none;
            transition: background-color .075s cubic-bezier(.19, .91, .38, 1), box-shadow .075s cubic-bezier(.19, .91, .38, 1);
            transition-behavior: normal, normal;
            user-select: none;
        }

        @media (-ms-high-contrast:active) {
            .button-51 {
                border-image: none 100% 1 0 stretch;
                border-style: none;
            }
        }

        .button-51__Content {
            align-items: center;
            display: flex;
            font-size: .8125rem;
            justify-content: center;
            letter-spacing: normal;
            line-height: 1.25rem;
            min-height: .0625rem;
            min-width: .0625rem;
            position: relative;
            transition: transform 75ms cubic-bezier(.19, .91, .38, 1);
            transition-behavior: normal;
        }

        @media (min-width: 48em) {
            .button-51__Content {
                font-size: .75rem;
                line-height: 1rem;
            }
        }

        .button-51::after {
            border-radius: .25rem;
            bottom: -.0625rem;
            box-shadow: 0 0 0 -.0625rem #005bd3;
            content: "";
            display: block;
            left: -.0625rem;
            pointer-events: none;
            position: absolute;
            right: -.0625rem;
            top: -.0625rem;
            z-index: 1;
        }

        .button-51:focus-visible {
            outline: 2px solid rgb(0, 91, 211);
            outline-offset: .0625rem;
        }

        .button-51:hover {
            background: #f7f7f7;
            box-shadow: inset 0 -0.0625rem 0 #ccc, inset 0.0625rem 0 0 #ebebeb, inset -0.0625rem 0 0 #ebebeb, inset 0 0.0625rem 0 #ebebeb;
        }

        .button-51:focus,
        .button-51:focus-visible {
            box-shadow: inset 0 -0.0625rem 0 #b5b5b5, inset -0.0625rem 0 0 #e3e3e3, inset 0.0625rem 0 0 #e3e3e3, inset 0 0.0625rem 0 #e3e3e3;
        }

        .button-51:active {
            background: #f7f7f7;
            box-shadow: 0rem 0.125rem 0.0625rem 0rem rgba(26, 26, 26, .2) inset, 0.0625rem 0rem 0.0625rem 0rem rgba(26, 26, 26, .12) inset, -0.0625rem 0rem 0.0625rem 0rem rgba(26, 26, 26, .12) inset;
        }

        .button-51:focus-visible::after {
            content: none;
        }

        .button-51:active::after {
            border-style: none;
            box-shadow: none;
        }

        .button-51:active .button-51__Content {
            transform: translateY(.0625rem);
        }

        .button-51 .button-51__Content {
            width: 100%;
        }

        .button-51.button-51 .button-51__Content {
            font-weight: 650;
        }

        /* CSS */
        .button-50 {
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
            box-sizing: border-box;
            font-size: .8125rem;
            font-weight: 450;
            letter-spacing: normal;
            -webkit-tap-highlight-color: transparent;
            align-items: center;
            border-radius: .5rem;
            border-style: none;
            cursor: pointer;
            display: inline-flex;
            justify-content: center;
            line-height: 1;
            margin: 0;
            min-height: 1.75rem;
            min-width: 1.75rem;
            padding: .375rem .75rem;
            text-align: center;
            text-decoration: none;
            transition: background-color .075s cubic-bezier(.19, .91, .38, 1), box-shadow .075s cubic-bezier(.19, .91, .38, 1);
            transition-behavior: normal, normal;
            user-select: none;

            background: #303030;
            border-color: transparent;
            border-width: 0;
            box-shadow: 0 .1875rem .0625rem -.0625rem rgba(26, 26, 26, .07);
            color: #fff;
            position: relative;
        }

        @media (-ms-high-contrast:active) {
            .button-50 {
                border-image: none 100% 1 0 stretch;
                border-style: none;
            }
        }

        .button-50__Content {
            align-items: center;
            display: flex;
            font-size: .8125rem;
            justify-content: center;
            letter-spacing: normal;
            line-height: 1.25rem;
            min-height: .0625rem;
            min-width: .0625rem;
            position: relative;
            transition: transform 75ms cubic-bezier(.19, .91, .38, 1);
            transition-behavior: normal;
        }

        @media (min-width: 48em) {
            .button-50__Content {
                font-size: .75rem;
                line-height: 1rem;
            }
        }

        .button-50::after {
            border-radius: .25rem;
            bottom: -.0625rem;
            box-shadow: 0 0 0 -.0625rem #005bd3;
            content: "";
            display: block;
            left: -.0625rem;
            pointer-events: none;
            position: absolute;
            right: -.0625rem;
            top: -.0625rem;
            z-index: 1;
        }

        .button-50::before {
            background-color: initial;
            background-image: linear-gradient(rgba(255, 255, 255, .07) 80%, rgba(255, 255, 255, .15));
            border-radius: .5625rem;
            bottom: 0;
            box-shadow: 0 .125rem 0 0 rgba(255, 255, 255, .2) inset, .125rem 0 0 0 rgba(255, 255, 255, .2) inset, -.125rem 0 0 0 rgba(255, 255, 255, .2) inset, 0 -.0625rem 0 .0625rem #000 inset, 0 .0625rem 0 0 #000 inset;
            content: "";
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transition: opacity 75ms cubic-bezier(.19, .91, .38, 1);
            transition-behavior: normal;
        }

        .button-50:focus-visible {
            box-shadow: 0 .1875rem .0625rem -.0625rem rgba(26, 26, 26, .07);
            outline: 2px solid rgb(0, 91, 211);
            outline-offset: .0625rem;
        }

        .button-50:hover {
            box-shadow: inset 0 -.0625rem 0 #ccc, inset .0625rem 0 0 #ebebeb, inset -.0625rem 0 0 #ebebeb, inset 0 .0625rem 0 #ebebeb;
        }

        .button-50:hover {
            background: #1a1a1a;
            border-color: transparent;
            color: #e3e3e3;
        }

        .button-50:focus {
            border-color: transparent;
            box-shadow: 0 .1875rem .0625rem -.0625rem rgba(26, 26, 26, .07);
        }

        .button-50:active {
            border-color: transparent;
        }

        .button-50:not(.button-50--disabled) {
            box-shadow: none;
        }

        .button-50:active {
            background: #1a1a1a;
            box-shadow: 0 .1875rem 0 0 #000 inset;
            color: #ccc;
        }

        .button-50:focus-visible::after {
            content: none;
        }

        .button-50:active::after {
            border-style: none;
            box-shadow: none;
        }

        .button-50:hover::before {
            box-shadow: 0 .125rem 0 0 rgba(255, 255, 255, .2) inset, .125rem 0 0 0 rgba(255, 255, 255, .2) inset, -.125rem 0 0 0 rgba(255, 255, 255, .2) inset, 0 -.0625rem 0 .0625rem #000 inset, 0 .0625rem 0 0 #000 inset;
        }

        .button-50:active::before {
            background-color: initial;
            background-image: linear-gradient(rgba(255, 255, 255, .1), rgba(255, 255, 255, 0));
            box-shadow: 0 .1875rem 0 0 #000 inset;
            opacity: 1;
        }

        .button-50:active .button-50__Content {
            transform: translateY(.0625rem);
        }

        .button-50 .button-50__Content {
            width: 100%;
        }

        .button-50.button-50 .button-50__Content {
            font-weight: 650;
        }


        button:hover {
            background-color: #d16c23;
        }

        @media (max-width: 480px) {
            h4 {
                font-size: 18px;
                margin-bottom: 15px;
            }

            td input,
            td select,
            button {
                font-size: 14px;
                padding: 8px;
            }

            button {
                padding: 10px;
                font-size: 14px;
            }
        }

        /* Utility classes */
        .flex {
            display: flex;
        }

        .justify-center {
            justify-content: center;
        }

        .items-center {
            align-items: center;
        }

        .fixed {
            position: fixed;
        }

        .inset-0 {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .z-50 {
            z-index: 50;
        }

        .bg-black {
            background-color: #000;
        }

        .bg-opacity-50 {
            opacity: 0.5;
        }

        .bg-white {
            background-color: #fff;
        }

        .p-6 {
            padding: 1.5rem;
        }

        .rounded {
            border-radius: 0.375rem;
        }

        .shadow-lg {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .text-center {
            text-align: center;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .cursor-not-allowed {
            cursor: not-allowed;
        }

        .text-xl {
            font-size: 1.25rem;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-gray-700 {
            color: #4a4a4a;
        }

        .text-gray-500 {
            color: #6b7280;
        }

        .bg-green-500 {
            background-color: #28a745;
        }

        .bg-red-500 {
            background-color: #dc3545;
        }

        .text-white {
            color: white;
        }

        .border-0 {
            border: none;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .hover\:bg-green-600:hover {
            background-color: #218838;
        }

        .hover\:bg-red-600:hover {
            background-color: #c82333;
        }

        .focus\:outline-none:focus {
            outline: none;
        }

        /* Modal specific styles */
        .x-modal-overlay {
            background-color: rgba(0, 0, 0, 0.6);
        }

        .x-modal-container {
            max-width: 400px;
            width: 100%;
            background-color: #15171d;
            padding: 1.5rem;
            border-radius: 0.375rem;
            box-shadow: 0 4px 8px rgba(121, 120, 120, 0.2);
        }

        .x-modal-container h5 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #cecece;
        }

        .x-modal-container p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 1rem;
        }

        .x-modal-container .btn {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .x-modal-container .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .x-modal-container .btn-success:hover {
            background-color: #218838;
        }

        .x-modal-container .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .x-modal-container .btn-danger:hover {
            background-color: #c82333;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .x-modal-container {
                padding: 1rem;
            }

            .x-modal-container h3 {
                font-size: 1.25rem;
            }

            .x-modal-container p {
                font-size: 0.9rem;
            }
        }

        #namer {
 position: relative;
 max-width: 400px;
 margin: 150px auto 0;
 background-color: #15171d;s
}

#namer input {
 border: 0;
 border-bottom: 2px solid #1976D2;
 width: 100%;
 font-size: 30px;
 line-height: 35px;
 height: 70px;
 text-align: center;
 padding: 10px;
 background: transparent;
  color: #BBDEFB;
}

#namer input.shake {
 animation-name: shaker;
 animation-duration: 200ms;
 animation-timing-function: ease-in-out;
 animation-delay: 0s;
}

#namer input:focus {
 outline: 0;
  color: #BBDEFB;
}

#namer input::placeholder {
 color: #1976D2;
}

.namer-controls {
 position: relative;
 display: block;
 height: 30px;
 margin: 20px 0;
 text-align: center;
 opacity: 0.3;
 cursor: not-allowed;
}

.namer-controls.active {
 opacity: 1;
 cursor: pointer;
}

.namer-controls div {
 float: left;
 width: 33.33%;
}

.namer-controls div span {
 box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.25);
 padding: 10px 5px;
 width: 95%;
 display: inline-block;
 margin-right: 5%;
 border-radius: 30px;
 font-size: 14px;
 text-transform: uppercase;
 letter-spacing: 0.3px;
}

.namer-controls div span:last-child {
 margin-right: 0;
}

.namer-controls div span.active {
 box-shadow: none;
 background-color: #1976D2;
 color: #fff;
}

#namer-input.serious input {
 letter-spacing: 2px;
 text-transform: uppercase;
 font-family: 'Andada', serif;
 font-weight: 500;
}

#namer-input.modern input {
 font-family: 'Raleway', sans-serif;
 text-transform: lowercase;
 font-weight: 300;
 letter-spacing: 10px;
}

#namer-input.cheeky input {
 font-family: 'Permanent Marker', cursive;
 font-size: 40px;
}

@keyframes shaker {
 0% {
  transform: translate(0px, 0px) rotate(0deg);
  opacity: 0.8;
 }
 10% {
  transform: translate(10px, 7px) rotate(-9deg);
  opacity: 0.6;
 }
 20% {
  transform: translate(13px, -19px) rotate(-3deg);
  opacity: 0.3;
 }
 30% {
  transform: translate(-6px, -6px) rotate(2deg);
  opacity: 0.4;
 }
 40% {
  transform: translate(-9px, -18px) rotate(-5deg);
  opacity: 0.4;
 }
 50% {
  transform: translate(10px, -8px) rotate(5deg);
  opacity: 0.7;
 }
 60% {
  transform: translate(-10px, 14px) rotate(-6deg);
  opacity: 1;
 }
 70% {
  transform: translate(10px, 3px) rotate(6deg);
  opacity: 0.1;
 }
 80% {
  transform: translate(-2px, 20px) rotate(-6deg);
  opacity: 1;
 }
 90% {
  transform: translate(-7px, -19px) rotate(2deg);
  opacity: 0.5;
 }
}


    </style>
</head>

<body>
    <div class="pricing-table">
        <h1 class="title">Exclusive Beats <i class="fas fa-music"></i></h1>
        <p class="subtitle">Genres not listed below are considered Custom Beats</p>
        {{-- <table>
            <thead>
                <tr>
                    <th><i class="fa-solid fa-list"></i> Genre Categories</th>
                    <th><i class="fas fa-dollar-sign"></i> Prices</th>
                    <th>
                        Descriptions
                        <i class="fas fa-info-circle" id="openInfoModal" style="cursor: pointer; color: #eb7c2f;"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><i class="fas fa-headphones"></i> Hip Hop / Local</td>
                    <td>100</td>
                    <td></i> Rhythm reference: <i class="fa-solid fa-check-circle"></i><br> Chorus reference: &nbsp;<i
                            class="fa-solid fa-times-circle"></i></td>
                </tr>
                <tr>
                    <td><i class="fas fa-drum"></i> Sesotho Fesheneng</td>
                    <td>140</td>
                    <td></i> Rhythm reference: <i class="fa-solid fa-check-circle"></i><br> Chorus reference: &nbsp;<i
                            class="fa-solid fa-check-circle"></i></td>
                </tr>
                <tr>
                    <td><i class="fas fa-guitar"></i> Afrobeat</td>
                    <td>100</td>
                    <td></i> Rhythm reference: <i class="fa-solid fa-check-circle"></i><br> Chorus reference: &nbsp;<i
                            class="fa-solid fa-check-circle"></i></td>
                </tr>
                <tr>
                    <td><i class="fas fa-microphone"></i> Trap</td>
                    <td>100</td>
                    <td></i> Rhythm reference: <i class="fa-solid fa-check-circle"></i>
                        <br> Chorus reference: &nbsp;<i class="fa-solid fa-times-circle"></i>
                    </td>

                </tr>
                <tr>
                    <td><i class="fas fa-drum"></i> Amapiano</td>
                    <td>140</td>
                    <td></i> Rhythm reference: <i class="fa-solid fa-check-circle"></i><br> Chorus reference: &nbsp;<i
                            class="fa-solid fa-check-circle"></i></td>
                </tr>
                <tr>
                    <td><i class="fas fa-star"></i> Custom Beat</td>
                    <td>160</td>
                    <td></i> Rhythm reference: <i class="fa-solid fa-check-circle"></i><br> Chorus reference: &nbsp;<i
                            class="fa-solid fa-check-circle"></td>
                </tr>
            </tbody>
        </table>
 --}}

        @livewire('inquiry-form')

        <section class="info-section">
            <h2 class="info-title" id="openInfoModal2" style="cursor: pointer;">Additional Information <i
                    class="fas fa-info-circle"></i></h2>
        </section>



        <!-- Modal Structure -->
        <div id="infoModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeModal">&times;</span>
                <h2><i class="fas fa-info-circle"></i> Additional Information</h2>
                <div class="modal-info">
                    <div class="modal-box">
                        <h3><i class="fas fa-wave-square"></i> Rhythm Reference</h3>
                        <p>
                            A rhythm reference refers to the ability to create a custom beat based on the artist's
                            rhythm
                            input or rhythmic arrangement.
                            This ensures the beat aligns with the artist's unique musical style and timing requirements.
                        </p>
                    </div>
                    <div class="modal-box">
                        <h3><i class="fas fa-microphone-lines"></i> Chorus Reference</h3>
                        <p>
                            A chorus reference refers to the process of developing a beat that incorporates the artist's
                            provided vocal ideas or chorus arrangement.
                            This approach allows for the seamless integration of the artist's vocal concepts into the
                            final
                            beat composition.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Get modal elements
                const infoModal = document.getElementById('infoModal');
                const openModalButton = document.getElementById('openInfoModal2');
                const closeModalButton = document.getElementById('closeModal');
                // Open modal when clicking the info icon
                openModalButton.addEventListener('click', () => {
                    infoModal.style.display = 'block';
                });
                // Close modal when clicking the close button
                closeModalButton.addEventListener('click', () => {
                    infoModal.style.display = 'none';
                });
                // Close modal when clicking outside the modal content
                window.addEventListener('click', (event) => {
                    if (event.target === infoModal) {
                        infoModal.style.display = 'none';
                    }
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Get modal elements
                const infoModal = document.getElementById('infoModal');
                const openModalButton = document.getElementById('openInfoModal');
                const closeModalButton = document.getElementById('closeModal');
                // Open modal when clicking the info icon
                openModalButton.addEventListener('click', () => {
                    infoModal.style.display = 'block';
                });
                // Close modal when clicking the close button
                closeModalButton.addEventListener('click', () => {
                    infoModal.style.display = 'none';
                });
                // Close modal when clicking outside the modal content
                window.addEventListener('click', (event) => {
                    if (event.target === infoModal) {
                        infoModal.style.display = 'none';
                    }
                });
            });
        </script>

        @livewireScripts
</body>

</html>
