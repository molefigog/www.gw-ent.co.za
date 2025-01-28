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
    <meta property="og:image" content="{{asset('storage/images/eyUeDXi0xqzpboxlMQcdECQtZfMARAd8VcSTw8xV.jpg')}}">
    <meta property="og:description" content=" A rhythm reference refers to the ability to create a custom beat based on the artist's rhythm
                        input or rhythmic arrangement.
                        This ensures the beat aligns with the artist's unique musical style and timing requirements. A chorus reference refers to the process of developing a beat that incorporates the artist's
                        provided vocal ideas or chorus arrangement.
                        This approach allows for the seamless integration of the artist's vocal concepts into the final
                        beat composition.">
    <meta property="og:url" content="https://www.gw-ent.co.za/beat-pricing" />
    <meta name="keywords" content="Genius Works beats Pricing, Beats, Lesotho Beats, GOG beats">
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

    td input, td select {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    td input:focus, td select:focus {
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

  button:hover {
        background-color: #d16c23;
    }

    @media (max-width: 480px) {
        h4 {
            font-size: 18px;
            margin-bottom: 15px;
        }

        td input, td select, button {
            font-size: 14px;
            padding: 8px;
        }

        button {
            padding: 10px;
            font-size: 14px;
        }
    }
    </style>
</head>

<body>
    <div class="pricing-table">
        <h1 class="title">Exclusive Beats <i class="fas fa-music"></i></h1>
        <p class="subtitle">Genres not listed below are considered Custom Beats</p>
        <table>
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

        <h1 class="title">Non Exclusive Beats <i class="fas fa-volume-up"></i></h1>
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-list"></i> Genre Categories</th>
                    <th><i class="fas fa-dollar-sign"></i> Prices</th>
                    <th><i class="fas fa-info-circle"></i> Descriptions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><i class="fas fa-headphones"></i> Hip Hop / Local</td>
                    <td>80</td>
                    <td> Rhythm reference: <i class="fa-solid fa-times-circle"></i><br> Chorus reference: &nbsp;<i
                            class="fa-solid fa-times-circle"></i></td>
                </tr>
                <tr>
                    <td><i class="fas fa-drum"></i> Sesotho Fesheneng</td>
                    <td>100</td>
                    <td> Rhythm reference: <i class="fa-solid fa-times-circle"></i><br> Chorus reference: &nbsp;<i
                            class="fa-solid fa-times-circle"></i></td>
                </tr>
                <tr>
                    <td><i class="fas fa-guitar"></i> Afrobeat</td>
                    <td>80</td>
                    <td> Rhythm reference: <i class="fa-solid fa-times-circle"></i><br> Chorus reference: &nbsp;<i
                            class="fa-solid fa-times-circle"></i></td>
                </tr>
                <tr>
                    <td><i class="fas fa-microphone"></i> Trap</td>
                    <td>100</td>
                    <td> Rhythm reference: <i class="fa-solid fa-times-circle"></i><br> Chorus reference: &nbsp;<i
                            class="fa-solid fa-times-circle"></i></td>
                </tr>
                <tr>
                    <td><i class="fas fa-drum"></i> Amapiano</td>
                    <td>80</td>
                    <td> Rhythm reference: <i class="fa-solid fa-times-circle"></i><br> Chorus reference: &nbsp;<i
                            class="fa-solid fa-times-circle"></i></td>
                </tr>
            </tbody>
        </table>

        <section class="info-section">

            <h2 class="info-title" id="openInfoModal2" style="cursor: pointer;">Additional Information <i
                    class="fas fa-info-circle"></i></h2>
        </section>

        <div class="container">
            <h4 class="title">Inquiry Form</h4>
            <form id="whatsapp-form" action="https://api.whatsapp.com/send" method="get" target="_blank">
                <table>
                    <tr>
                        <td>
                            <label for="type">Type:</label>
                            <select id="type" name="type" required>
                                <option value="Exclusive">Exclusive</option>
                                <option value="Non Exclusive">Non Exclusive</option>
                            </select>
                        </td>
                        <td>
                            <label for="genre">Genre:</label>
                            <select id="genre" name="genre" required>
                                <option value="Hip Hop / Local">Hip Hop / Local</option>
                                <option value="Sesotho Fesheneng">Sesotho Fesheneng</option>
                                <option value="Afrobeat">Afrobeat</option>
                                <option value="Trap">Trap</option>
                                <option value="Amapiano">Amapiano</option>
                                <option value="Custom Beat">Custom Beat</option>
                            </select>
                        </td>
                        <td>
                            <label for="price">Price:</label>
                            <input type="text" id="price" name="price" style="width: 40px;" readonly required>
                        </td>
                    </tr>
                </table>
        
                <input type="hidden" name="phone" value="26659073443">
                <input type="hidden" name="text" id="message-text">
                
                <div style="text-align: center;">
                    <button type="submit">Send to WhatsApp</button>
                </div>
            </form>
        </div>




    <!-- Modal Structure -->
    <div id="infoModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h2><i class="fas fa-info-circle"></i> Additional Information</h2>
            <div class="modal-info">
                <div class="modal-box">
                    <h3><i class="fas fa-wave-square"></i> Rhythm Reference</h3>
                    <p>
                        A rhythm reference refers to the ability to create a custom beat based on the artist's rhythm
                        input or rhythmic arrangement.
                        This ensures the beat aligns with the artist's unique musical style and timing requirements.
                    </p>
                </div>
                <div class="modal-box">
                    <h3><i class="fas fa-microphone-lines"></i> Chorus Reference</h3>
                    <p>
                        A chorus reference refers to the process of developing a beat that incorporates the artist's
                        provided vocal ideas or chorus arrangement.
                        This approach allows for the seamless integration of the artist's vocal concepts into the final
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
    <Script>
        const priceMapping = {
            "Exclusive": {
                "Hip Hop / Local": "100",
                "Sesotho Fesheneng": "140",
                "Afrobeat": "100",
                "Trap": "100",
                "Amapiano": "140",
                "Custom Beat": "160"
            },
            "Non Exclusive": {
                "Hip Hop / Local": "80",
                "Sesotho Fesheneng": "100",
                "Afrobeat": "80",
                "Trap": "100",
                "Amapiano": "80",
                "Custom Beat": "N/A"
            }
        };

        document.getElementById('type').addEventListener('change', updatePrice);
        document.getElementById('genre').addEventListener('change', updatePrice);

        function updatePrice() {
            const type = document.getElementById('type').value;
            const genre = document.getElementById('genre').value;

            // Get the price based on selected type and genre
            const price = priceMapping[type][genre];
            document.getElementById('price').value = price;

            // Generate the message for WhatsApp
            const message = `I would like to inquire about a ${type} beat. Genre: ${genre}. Price: ${price}.`;
            document.getElementById('message-text').value = message;
        }

        // Initialize price on page load
        updatePrice();

    </Script>
</body>

</html>