<?php
namespace core;

class OrderStatus {
    public const NEW       = 1; // NOWE
    public const ACCEPTED  = 2; // PRZYJETE
    public const READY     = 3; // GOTOWE
    public const PICKED_UP = 4; // ODEBRANE (historia)
    public const REJECTED  = 5; // ODRZUCONE
}
