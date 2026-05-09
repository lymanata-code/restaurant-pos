<?php

namespace App\Http\Controllers\Admin\KitchenTickets;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\KitchenTicket;

class KitchenTicketController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return KitchenTicket::class; }
    protected function viewPath(): string { return 'admin.kitchen_tickets'; }
    protected function routeName(): string { return 'admin.kitchen-tickets'; }
    protected function translationNamespace(): string { return 'kitchen_tickets'; }
}