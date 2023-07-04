<?php

namespace DMarti\ExamplesSymfony5\Constant;

enum CustomerOrderStatusFulfillment: string
{
    case Pending = 'Pending';
    case Packed = 'Packed';
    case Cancelled = 'Cancelled';
}
