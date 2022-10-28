<?php

declare(strict_types=1);

namespace OurEnergy\WitsApi\Enums;

enum Schedule: string
{
    case FINAL = "Final";
    case INTERIM = "Interim";
    case NRSL = "NRSL";
    case NRSS = "NRSS";
    case PRSL = "PRSL";
    case PRSS = "PRSS";
    case RTD = "RTD";
    case RTP = "RTP";
    case RTP_AVERAGE = "RTPAverage";
    case WDS = "WDS";
}