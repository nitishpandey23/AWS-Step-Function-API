<?php

// Specify the ARN of your Step Function
$step_function_arn = 'arn:aws:states:ap-south-1:304003606207:stateMachine:AutomatedEC2Management';

// Check if the $step_function_arn is not empty
if (empty($step_function_arn)) {
    die("Error: Step Function ARN is empty.");
}

// Function to execute the Step Function
function triggerStepFunction() {
    global $step_function_arn;

    // Specify AWS credentials
    putenv("AWS_ACCESS_KEY_ID=AKIAUNSAG427RCW6PBMW");
    putenv("AWS_SECRET_ACCESS_KEY=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");

    // Specify the full path to the aws executable
    $aws_executable = "/usr/local/bin/aws";

    // Set PATH environment variable
    putenv("PATH=/usr/local/bin:" . getenv("PATH"));

    // Construct the AWS CLI command
    $command = "$aws_executable stepfunctions start-execution --state-machine-arn $step_function_arn --region ap-south-1 2>&1";

    // Log the command
    error_log("Executing command: $command");

    // Execute the command and capture output
    exec($command, $output, $return_var);

    // Log the output
    error_log("Command output: " . implode("\n", $output));

    // Log the return code
    error_log("Return code: $return_var");

    if ($return_var !== 0) {
        $status = "Failed with error code $return_var";
    } else {
        $status = "Success";
    }

    return $status;
}

// Check if the button is clicked
if (isset($_POST['trigger_button'])) {
    $execution_status = triggerStepFunction();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trigger Step Function</title>
    <!-- Your existing styles and head content -->
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://www.cogenerate.in/assets/img/logo.png');
            background-size: contain;
            background-position: center; /* Center the background image */
            background-repeat: no-repeat;
            color: white;
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            font-size: 2em;
        }

        button {
            padding: 15px 30px;
            font-size: 1.2em;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 20px;
            font-size: 1.2em;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 1.5em;
            }

            button {
                padding: 10px 20px;
                font-size: 1em;
            }

            p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <h1>Trigger Auto-Scaling</h1>

    <?php if (isset($execution_status)): ?>
        <p>AWS Step Function execution status: <?php echo $execution_status; ?></p>
    <?php endif; ?>

    <form method="post">
        <button type="submit" name="trigger_button">Auto-Scaling</button>
    </form>
</body>
</html>
