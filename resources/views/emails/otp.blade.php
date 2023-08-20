<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Code</title>
</head>

<body style="margin: 0; font-family: sans-serif; background-color: #f7fafc;">
    <table
        style="width: 100%; max-width: 400px; margin: 20px auto; background-color: #ffffff; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); border-radius: 8px; overflow: hidden;">
        <tr>
            <td
                style="padding: 20px; display: flex; flex-direction:column; justify-content:center; align-items: center;">
                <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 10px; color: #3490dc;">Hello,
                    {{ $user->first_name }} </h1>
                <h4 style="font-size: 16px; color: #4a5568;">Your OTP code to verify your email is </h4>
                <h4 style="font-size: 24px; margin-top: 6px; border: 1px solid ; padding: 10px; text-align: center;">{{ $otpCode }}</h4>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; background-color: #edf2f7;">
                <h5 style="font-size: 14px; color: #4a5568;">Please do not share that with anyone</h5>
            </td>
        </tr>
    </table>
</body>

</html>
