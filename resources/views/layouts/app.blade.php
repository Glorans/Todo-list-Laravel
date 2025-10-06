<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Todo App</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #fdf6e3;
            background-image: linear-gradient(to bottom, rgba(0,0,0,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: black;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            font-family: 'Permanent Marker', cursive;
        }

        .subheading {
            text-align: center;
            color: #333;
            font-size: 16px;
            margin: 0 0 18px;
            font-style: italic;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.15);
        }

        .add-task-section {
            max-width: 450px;
            margin: 0 auto 18px;
            display: flex;
            flex-direction: column;
            width: 100%;
        }



        .add-task-input,
        .add-task-detail-input {
            width: 100%; 
            padding: 12px 16px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.12);
            background: #fff;
            margin-bottom: 5px; 
        }

        .detail-and-button { display: flex; gap: 10px; }
        .detail-and-button .add-task-detail-input { flex: 1; }

        .add-task-btn {
            padding: 12px 20px;
            background: linear-gradient(135deg, #2ed573, #07700c);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            white-space: nowrap;
            box-shadow: 0 2px 10px rgba(0,0,0,0.12);
        }

        .status-column {
            width: 100%;
            max-width: 450px;
            margin: 20px auto;
            background: #fff9c4;
            border-radius: 12px;
            border: 1px solid #e7dea7;
            box-shadow: 4px 4px 12px rgba(0,0,0,0.15);
            padding: 16px;
            min-height: 520px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .column-header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
        }

        .status-column h2 {
            margin: 6px 0;
            text-align: center;
            color: #8a6d00;
            font-weight: 800;
        }

        .task-counter {
            position: absolute;
            top: 12px;
            right: 16px;
            font-size: 13px;
            color: #5a5300;
            background: #fff3bf;
            padding: 4px 8px;
            border-radius: 999px;
            font-weight: 700;
            border: 1px solid #e7dea7;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
        }

        .column-divider {
            border: 0;
            border-top: 2px solid rgba(255,255,255,0.9);
            margin: 12px 0 14px;
        }

        .card {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            background: linear-gradient(180deg, #ffd27f, #ffbd59);
            border-radius: 12px;
            padding: 12px 14px;
            margin: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            color: #fff;
        }

        .card.inprogress {
            background: linear-gradient(135deg, #a13030ff, #f56d84ff);
        }

        .card.done {
            background: linear-gradient(135deg, #2f7e40ff, #59d64eff);
            
        }

        .text-wrap { flex: 1; min-width: 0; }

        .task-text {
            display: block;
            font-weight: 700;
            outline: none;
        }

        .task-detail {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: #f1f1f1;
            opacity: 0.9;
        }

        .card.done .task-detail {
            color: #1f3b1f;
        }

        .icon-group {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 8px;
            background: transparent;
            padding: 0;
            z-index: 2;
        }

        .edit-btn, .progress-btn, .move-btn, .delete-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: none;
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
            backdrop-filter: blur(1.5px);
            background: rgba(255, 255, 255, 0.16);
        }

        .progress-btn, .edit-btn:hover, .move-btn:hover, .delete-btn:hover { background: rgba(255,255,255,0.28); }

        .edit-form {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            z-index: 1000;
            max-width: 400px;
            width: 90%;
        }

        .edit-form.active { display: block; }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .overlay.active { display: block; }

        .edit-form input, .edit-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .edit-form button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .save-btn {
            background: #2ed573;
            color: white;
        }

        .cancel-btn {
            background: #ddd;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>