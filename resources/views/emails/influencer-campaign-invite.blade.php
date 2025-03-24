<!DOCTYPE html>
<html>

<head>
    <title>Campaign Invite</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        ul {
            padding-left: 1.5em;
        }

        ul li {
            margin-bottom: 8px;
        }

        ul {
            list-style: none;
        }

        ul li:before {
            content: '✓';
        }

        .btn {
            display: inline-block;
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>

<body style="padding: 20px; background-color: #f0f0f0; padding: 100px 0;">

    <section
        style="width: 70%; margin-right: auto; margin-left: auto; border: 1px solid #f9f9f9; border-radius: 12px; padding: 30px 20px; background-color: white;">
        <p style="font-size: 20px; color:#222222; font-weight: bold; font-family: sans-serif;">Hi {{ $influencerName }},
        </p>

        <p style="font-size: 16px; font-family: sans-serif;">We’re thrilled to invite you to join an exclusive campaign
            that’s perfectly tailored to your unique influence and
            style. Here’s a quick overview of the campaign:</p>

        <ul style="font-size: 14px; font-family: sans-serif; margin: 25px 0px; border-radius: 9px; background-color: #f9f9f9; padding: 30px 10px; ">
            @isset($campaignTitle)
                <li>
                    <strong style="margin-right: 6px;">&#10003;</strong>
                    <strong>🌟 Campaign Name:</strong> {{ $campaignTitle }}
                </li>
            @endisset

            @isset($brandName)
                <li>
                    <strong style="margin-right: 6px;">&#10003;</strong>
                    <strong>💼 Brand:</strong> {{ $brandName }}
                </li>
            @endisset

            @isset($targetAudience)
                <li>
                    <strong style="margin-right: 6px;">&#10003;</strong>
                    <strong>🎯 Target Audience:</strong> {{ $targetAudience }}
                </li>
            @endisset

            @isset($compensation)
                <li>
                    <strong style="margin-right: 6px;">&#10003;</strong>
                    <strong>💰 Compensation:</strong> {{ $compensation }}
                </li>
            @endisset

            @isset($acceptanceDeadline)
                <li>
                    <strong style="margin-right: 6px;">&#10003;</strong>
                    <strong>🗓 Deadline to Accept:</strong> {{ $acceptanceDeadline }}
                </li>
            @endisset
        </ul>


        <p style="font-size: 16px; font-family: sans-serif;">If you’re ready to showcase your talent and make an impact,
            simply click the link below to review and accept the
            campaign details:</p>

        <p style="font-size: 16px; font-family: sans-serif;"><a href="{{ $campaignLink }}" class="btn"
                style="color: white;">👉 Accept the Campaign Now</a></p>

        <p style="font-size: 16px; font-family: sans-serif;">We’re excited to work with you and can’t wait to see the
            amazing content you’ll create for this campaign. If you
            have any questions or need more details, don’t hesitate to reach out to us.</p>

        <p style="font-size: 16px; font-family: sans-serif;">Best regards,<br>
            <strong>{{env('APP_NAME')}} Team</strong>
            {{-- <strong>[Your App Name] Team</strong> --}}
        </p>
    </section>
</body>

</html>
