    <!-- Friendly Reminder Note - Compact Version -->
    <?php if ($role !== 'customer'): ?>
    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert" style="border-left: 6px solid #17a2b8; background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%); border-radius: 12px; padding: 1.25rem;">
        <div class="d-flex align-items-center">
            <div class="me-3" style="font-size: 1.8rem;">💡</div>
            <div class="flex-grow-1">
                <p class="mb-2" style="font-size: 1.2rem; line-height: 1.5; color: #0c5460; margin: 0;">
                    <strong>Friendly reminder:</strong> Always add customers to the system before creating milling orders. 
                    This ensures we provide the best possible service and keep everything organized for our valued customers! 
                    <span style="font-size: 1.3rem;">😊</span>
                </p>
            </div>
            <div class="ms-3">
                <?= $this->Html->link(
                    '<i class="bi bi-person-plus-fill me-1"></i>Add Customer', 
                    ['controller' => 'Users', 'action' => 'addcustomers'], 
                    ['class' => 'btn btn-info btn-lg', 'escape' => false]
                ) ?>
            </div>
            <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <?php endif; ?>

<div class="dashboard content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0" style="font-size: 2.5rem; font-weight: 700;"><?= h($dashboardTitle ?? 'Dashboard') ?></h1>
        <div class="text-muted" style="font-size: 1.3rem;">Welcome, <strong><?= h($identity->username ?? 'User') ?></strong></div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5">
        <?php if ($role === 'customer'): ?>
            <!-- Customer Dashboard -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 clickable-card" onclick="window.location='<?= $this->Url->build(['controller' => 'MillingOrders', 'action' => 'index']) ?>'">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-primary text-uppercase mb-1">Total Orders</div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= $millingOrdersCount ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-box-seam fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2 clickable-card" onclick="window.location='<?= $this->Url->build(['controller' => 'Payments', 'action' => 'index']) ?>'">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-success text-uppercase mb-1">Total Payments</div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= $paymentsCount ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-credit-card fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-info text-uppercase mb-1">Total Paid</div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800">₱<?= number_format($totalPaid ?? 0, 2) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-cash-coin fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-warning text-uppercase mb-1">Active Orders</div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= $millingOrdersCountActive ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-gear fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Staff/Owner Dashboard -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 clickable-card" onclick="window.location='<?= $this->Url->build(['controller' => 'Users', 'action' => 'customers']) ?>'">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-primary text-uppercase mb-1">Total Customers</div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= $customersCount ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2 clickable-card" onclick="window.location='<?= $this->Url->build(['controller' => 'Payments', 'action' => 'index']) ?>'">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-success text-uppercase mb-1">Total Payments</div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= $paymentsCount ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-credit-card fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2 clickable-card" onclick="window.location='<?= $this->Url->build(['controller' => 'MillingOrders', 'action' => 'index']) ?>'">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-info text-uppercase mb-1">Total Orders</div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= $millingOrdersCount ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-box-seam fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-warning text-uppercase mb-1">Total Revenue</div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800">₱<?= number_format($totalRevenue ?? 0, 2) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-cash-coin fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status Cards -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2 clickable-card" onclick="window.location='<?= $this->Url->build(['controller' => 'MillingOrders', 'action' => 'index', '?' => ['status' => 'Pending']]) ?>'">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-secondary text-uppercase mb-1">Pending Orders</div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= $pendingOrdersCount ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-clock fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2 clickable-card" onclick="window.location='<?= $this->Url->build(['controller' => 'MillingOrders', 'action' => 'index', '?' => ['status' => 'Milling']]) ?>'">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-warning text-uppercase mb-1">Milling Orders</div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= $millingOrdersCountActive ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-gear fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2 clickable-card" onclick="window.location='<?= $this->Url->build(['controller' => 'MillingOrders', 'action' => 'index', '?' => ['status' => 'Completed']]) ?>'">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-success text-uppercase mb-1">Completed Orders</div>
                                <div class="h2 mb-0 font-weight-bold text-gray-800"><?= $completedOrdersCount ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-check-circle fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Activity Section -->
    <div class="row">
        <?php if ($role !== 'customer'): ?>
            <!-- Recent Orders -->
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">Recent Orders</h4>
                        <?= $this->Html->link('View All', ['controller' => 'MillingOrders', 'action' => 'index'], ['class' => 'btn btn-lg btn-outline-primary']) ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentOrders)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recentOrders as $order): ?>
                                    <a href="<?= $this->Url->build(['controller' => 'MillingOrders', 'action' => 'view', $order->id]) ?>" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Order #<?= $order->id ?></h5>
                                            <small class="text-muted" style="font-size: 1rem;"><?= $order->created->timeAgoInWords() ?></small>
                                        </div>
                                        <p class="mb-1" style="font-size: 1.1rem;">Customer: <?= h($order->customer->name ?? 'Unknown') ?></p>
                                        <small class="text-muted" style="font-size: 1rem;">
                                            Weight: <?= h($order->weight) ?> kg | 
                                            Amount: ₱<?= number_format($order->total_amount, 2) ?>
                                        </small>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0" style="font-size: 1.2rem;">No recent orders found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-success">Recent Payments</h4>
                        <?= $this->Html->link('View All', ['controller' => 'Payments', 'action' => 'index'], ['class' => 'btn btn-lg btn-outline-success']) ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentPayments)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recentPayments as $payment): ?>
                                    <a href="<?= $this->Url->build(['controller' => 'Payments', 'action' => 'view', $payment->id]) ?>" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Payment #<?= $payment->id ?></h5>
                                            <small class="text-muted" style="font-size: 1rem;"><?= $payment->created->timeAgoInWords() ?></small>
                                        </div>
                                        <p class="mb-1" style="font-size: 1.1rem;">Customer: <?= h($payment->customer->name ?? 'Unknown') ?></p>
                                        <small class="text-muted" style="font-size: 1rem;">
                                            Amount: ₱<?= number_format($payment->amount, 2) ?> | 
                                            Method: <?= ucfirst($payment->payment_method) ?>
                                        </small>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0" style="font-size: 1.2rem;">No recent payments found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Customer Recent Activity -->
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">My Recent Orders</h4>
                        <?= $this->Html->link('View All', ['controller' => 'MillingOrders', 'action' => 'index'], ['class' => 'btn btn-lg btn-outline-primary']) ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentOrders)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recentOrders as $order): ?>
                                    <a href="<?= $this->Url->build(['controller' => 'MillingOrders', 'action' => 'view', $order->id]) ?>" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Order #<?= $order->id ?></h5>
                                            <small class="text-muted" style="font-size: 1rem;"><?= $order->created->timeAgoInWords() ?></small>
                                        </div>
                                        <p class="mb-1" style="font-size: 1.1rem;">Status: 
                                            <span class="badge 
                                                <?= $order->status === 'Pending' ? 'bg-secondary' :
                                                   ($order->status === 'Milling' ? 'bg-warning' : 'bg-success') ?>" style="font-size: 1rem;">
                                                <?= h($order->status) ?>
                                            </span>
                                        </p>
                                        <small class="text-muted" style="font-size: 1rem;">
                                            Weight: <?= h($order->weight) ?> kg | 
                                            Amount: ₱<?= number_format($order->total_amount, 2) ?>
                                        </small>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0" style="font-size: 1.2rem;">No recent orders found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-success">My Recent Payments</h4>
                        <?= $this->Html->link('View All', ['controller' => 'Payments', 'action' => 'index'], ['class' => 'btn btn-lg btn-outline-success']) ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentPayments)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recentPayments as $payment): ?>
                                    <a href="<?= $this->Url->build(['controller' => 'Payments', 'action' => 'view', $payment->id]) ?>" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">Payment #<?= $payment->id ?></h5>
                                            <small class="text-muted" style="font-size: 1rem;"><?= $payment->created->timeAgoInWords() ?></small>
                                        </div>
                                        <p class="mb-1" style="font-size: 1.1rem;">Amount: ₱<?= number_format($payment->amount, 2) ?></p>
                                        <small class="text-muted" style="font-size: 1rem;">
                                            Method: <?= ucfirst($payment->payment_method) ?> | 
                                            Date: <?= $payment->payment_date->format('M j, Y') ?>
                                        </small>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0" style="font-size: 1.2rem;">No recent payments found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.clickable-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.clickable-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.border-left-primary { border-left: 4px solid #4e73df !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }
.border-left-info { border-left: 4px solid #36b9cc !important; }
.border-left-warning { border-left: 4px solid #f6c23e !important; }
.border-left-secondary { border-left: 4px solid #858796 !important; }

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
}

.text-lg {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.list-group-item {
    border: none;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e3e6f0;
}

.list-group-item:last-child {
    border-bottom: none;
}

.list-group-item:hover {
    background-color: #f8f9fc;
}

.h2 {
    font-size: 2.5rem !important;
    font-weight: 700 !important;
}

.card-body {
    padding: 1.5rem !important;
}

.card-header {
    padding: 1.25rem 1.5rem !important;
}

/* Make icons larger */
.bi {
    font-size: 2.5rem !important;
}

/* Larger buttons */
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
    border-radius: 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click effects to clickable cards
    const clickableCards = document.querySelectorAll('.clickable-card');
    clickableCards.forEach(card => {
        card.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(-2px)';
        });
        card.addEventListener('mouseup', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>