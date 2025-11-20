<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chatbot</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .chat-container {
            width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .chat-box {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .chat-message {
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .user-message {
            background-color: #dcf8c6;
            text-align: right;
        }
        .bot-message {
            background-color: #f1f0f0;
        }
        .chat-input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .chat-submit {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-box" id="chat-box"></div>
        <input type="text" id="chat-input" class="chat-input" placeholder="Type your message...">
        <button onclick="sendMessage()" class="chat-submit">Send</button>
    </div>

    <script>
        async function sendMessage() {
            const chatBox = document.getElementById('chat-box');
            const chatInput = document.getElementById('chat-input');
            const userMessage = chatInput.value;

            if (userMessage.trim() === '') {
                return;
            }

            const userMessageDiv = document.createElement('div');
            userMessageDiv.classList.add('chat-message', 'user-message');
            userMessageDiv.textContent = userMessage;
            chatBox.appendChild(userMessageDiv);

            chatInput.value = '';

            const response = await fetch('process_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: userMessage })
            });

            const data = await response.json();
            const botMessageDiv = document.createElement('div');
            botMessageDiv.classList.add('chat-message', 'bot-message');
            botMessageDiv.textContent = data.response;
            chatBox.appendChild(botMessageDiv);

            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>
</body>
</html>
