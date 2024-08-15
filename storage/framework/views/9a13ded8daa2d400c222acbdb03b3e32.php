<?php $__env->startSection('content'); ?>
    <div class="">
        <h2>Bookings for <?php echo e($artist->name); ?></h2>

        <?php if(is_null($booking)): ?>
            <p>No bookings found for this artist.</p>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="invoice-title">
                                        <h3>
                                            <img src="<?php echo e(asset('storage/' . $booking->image)); ?>" alt="artist image" height="100">
                                        </h3>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6 text-start">
                                            <address>
                                                <strong>Info:</strong><br>
                                                Artist: <?php echo e($booking->artist); ?><br>
                                                <?php if(isset($booking->bank[0])): ?>
                                                    Bank: <?php echo e($booking->bank[0]['name']); ?><br>
                                                    Account: <?php echo e($booking->bank[0]['acc_number']); ?><br>
                                                <?php endif; ?>
                                                M-pesa: <?php echo e($booking->mpesa); ?>

                                            </address>
                                        </div>

                                        <div class="col-6 text-start">
                                            <address>
                                                <strong>Contact:</strong><br>
                                                <?php $__currentLoopData = $booking->contact; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    Tel: <?php echo e($contact['tell']); ?><br>
                                                    Tel: <?php echo e($contact['tell2']); ?><br>
                                                    Email: <?php echo e($contact['email']); ?><br>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Local Pricing Table -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h4>Local Pricing</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <td><strong>Price</strong></td>
                                                    <td class="text-center"><strong>Duration/Time</strong></td>
                                                    <td class="text-end"><strong>Transport</strong></td>
                                                    <td class="text-end"><strong>Total</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $localTotalPrice = 0;
                                                    $localTransportCost = isset($booking->transport[0]['local']) ? $booking->transport[0]['local'] : 0;
                                                ?>
                                                <?php $__currentLoopData = $booking->pricing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $localTotalPrice += $price['amount'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo e($price['amount']); ?></td>
                                                        <td class="text-center"><?php echo e($price['duration']); ?></td>
                                                        <td class="text-end"><?php echo e($localTransportCost); ?></td>
                                                        <td class="text-end">
                                                            <?php
                                                                $total = $price['amount'] + $localTransportCost;
                                                            ?>
                                                            <?php echo e(number_format($total, 2)); ?>

                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- International Pricing Table -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h4>International Pricing</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <td><strong>Price</strong></td>
                                                    <td class="text-center"><strong>Duration/Time</strong></td>
                                                    <td class="text-end"><strong>Transport</strong></td>
                                                    <td class="text-end"><strong>Total</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $internationalTotalPrice = 0;
                                                    $internationalTransportCost = isset($booking->transport[0]['south_africa']) ? $booking->transport[0]['south_africa'] : 0;
                                                ?>
                                                <?php $__currentLoopData = $booking->int_pricing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $int_price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $internationalTotalPrice += $int_price['amount'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo e($int_price['amount']); ?></td>
                                                        <td class="text-center"><?php echo e($int_price['duration']); ?></td>
                                                        <td class="text-end"><?php echo e($internationalTransportCost); ?></td>
                                                        <td class="text-end">
                                                            <?php
                                                                $total = $int_price['amount'] + $internationalTransportCost;
                                                            ?>
                                                            <?php echo e(number_format($total, 2)); ?>

                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="flex justify-content-center mt-3">
                                        <?php $__currentLoopData = $booking->contact; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a class="btn btn-outline-info btn-sm mx-2" href="mailto:<?php echo e($contact['email']); ?>"><i class="fas fa-envelope"></i> Email</a>
                                            <a class="btn btn-outline-info btn-sm mx-2" href="tel:<?php echo e($contact['tell']); ?>"><i class="fas fa-phone-square-alt"></i> Call</a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div> <!-- end row -->

                            <!-- PDF Download Button -->
                            <div class="text-right mt-4">
                                <a href="<?php echo e(route('generate.pdf', ['artistId' => $artist->id])); ?>" class="btn btn-primary"><i class="fa fa-print"></i>  Download PDF</a>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/bookings/artist.blade.php ENDPATH**/ ?>