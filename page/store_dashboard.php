<?php
if (!isset($_SESSION['username'])) {
?>
    <script>
        window.location.href = '?page=login';
    </script>
    <?php
} else {
    if (!$user_info['hasStore']) {
    ?>
        <script>
            window.location.href = '?page=home';
        </script>
    <?php
    } else {
    ?>
        <style>
            .chart {
                max-height: 300px !important;
            }
        </style>
        <div class='row'>
            <div class='col-md-4'>
                <div class='card'>
                    <div class='card-body'>
                        <small>TODAY</small>
                        <h3>10 Orders</h3>
                    </div>
                </div>
            </div>
            <div class='col-md-4'>
                <div class='card'>
                    <div class='card-body'>
                        <small>MONTH</small>
                        <h3>10 Orders</h3>
                    </div>
                </div>
            </div>
            <div class='col-md-4'>
                <div class='card'>
                    <div class='card-body'>
                        <small>YEAR</small>
                        <h3>10 Orders</h3>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class='row'>
            <div class='col-md-6'>
                <div class='card'>
                    <div style="margin-top: 10px; padding-right: 15px;">
                        <div class="p-2 bd-highlight">Order History</div>
                    </div>
                    <div class='card-body'>
                        <canvas id="order_history" class='chart' width="400" height="400"></canvas>
                        <script>
                            var ctx = document.getElementById('order_history');
                            var order_history = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                                    datasets: [{
                                        label: 'My First Dataset',
                                        data: [65, 59, 80, 81, 56, 55, 40],
                                        fill: false,
                                        borderColor: 'rgb(75, 192, 192)',
                                        tension: 0.1
                                    }]
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
            <div class='col-md-6'>
                <div class='card'>
                    <div style="margin-top: 10px; padding-right: 15px;">
                        <div class="p-2 bd-highlight">Order Status</div>
                    </div>
                    <div class='card-body'>
                        <div class='row'>
                            <canvas id="order_status" class='chart' width="400" height="400"></canvas>
                            <script>
                                var ctx = document.getElementById('order_status');
                                var order_status = new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: [
                                            'Red',
                                            'Blue',
                                            'Yellow'
                                        ],
                                        datasets: [{
                                            label: 'My First Dataset',
                                            data: [300, 50, 100],
                                            backgroundColor: [
                                                'rgb(255, 99, 132)',
                                                'rgb(54, 162, 235)',
                                                'rgb(255, 205, 86)'
                                            ],
                                            hoverOffset: 4
                                        }]
                                    }
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
