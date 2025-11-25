<?php
require 'vendor/autoload.php';

use OpenTelemetry\API\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\TracerProviderFactory;

// Initialize tracer provider
$tracerProvider = (new TracerProviderFactory())->create();
$tracer = $tracerProvider->getTracer('lab-online-app');

// Start a span for the incoming request
$requestSpan = $tracer->spanBuilder('incoming-request')->startSpan();

try {
    // Include your application sections once
    include "header.php";

    // Optional: wrap body in its own span
    $bodySpan = $tracer->spanBuilder('render-body')->startSpan();
    include "body.php";
    $bodySpan->end();

    include "newslettter.php";
    include "footer.php";

} catch (Exception $e) {
    $requestSpan->recordException($e);
}

$requestSpan->end();
?>
		
