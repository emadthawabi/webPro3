<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? $pageTitle . ' - Pathfinder' : 'Pathfinder'; ?></title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/CustomTour.css">
<link rel="stylesheet" type="text/css" href="css/ThingsToDo.css">
<link rel="stylesheet" type="text/css" href="css/tours.css">

<style>
    .error-message {
        color: #f44336;
        font-weight: 500;
        margin: 8px 0;
        font-size: 14px;
    }

    /* Animation for error messages that fade in */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .error-message.fade-in {
        animation: fadeIn 0.3s ease-in;
    }

    /* Optional class to make the text slightly larger */
    .error-message.large {
        font-size: 16px;
    }

    /* Optional class for critical errors that need more emphasis */
    .error-message.critical {
        font-weight: 700;
    }
</style>