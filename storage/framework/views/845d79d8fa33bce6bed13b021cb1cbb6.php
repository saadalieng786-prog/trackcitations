<table>
    <tbody>
    <tr>
        <td><strong>Generated At</strong></td>
        <td><?php echo e($summary['generated_at']); ?></td>
        <td><strong>Total Tickets</strong></td>
        <td><?php echo e($summary['ticket_count']); ?></td>
        <td><strong>Tickets With Point Data</strong></td>
        <td><?php echo e($summary['tickets_with_points']); ?></td>
    </tr>
    <tr>
        <td><strong>Original Points Total</strong></td>
        <td><?php echo e(number_format($summary['original_points_total'], 2)); ?></td>
        <td><strong>Final Points Total</strong></td>
        <td><?php echo e(number_format($summary['final_points_total'], 2)); ?></td>
        <td><strong>Points Saved Total</strong></td>
        <td><?php echo e(number_format($summary['points_saved_total'], 2)); ?></td>
    </tr>
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>Company</th>
        <th>Ticket Count</th>
        <th>Points Saved Total</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $summary['by_company']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $companySummary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($companySummary['company']); ?></td>
            <td><?php echo e($companySummary['ticket_count']); ?></td>
            <td><?php echo e(number_format($companySummary['points_saved_total'], 2)); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>Ticket Id</th>
        <th>Driver Name</th>
        <th>Driver Email</th>
        <th>Company</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zipcode</th>
        <th>Date Received</th>
        <th>Indicator</th>
        <th>Class Commercial?</th>
        <th>Roadside Inspection?</th>
        <th>Vehicle License Number</th>
        <th>Violation</th>
        <th>Citation Number</th>
        <th>DATAQ/DVER</th>
        <th>Ticket Type</th>
        <th>Beginning Fine Amount</th>
        <th>Final Fine Amount</th>
        <th>Total DVER Points</th>
        <th>Total DVER Points Removed</th>
        <th>Original Points Value</th>
        <th>Final Points Value</th>
        <th>Points Saved</th>
        <th>Court Name</th>
        <th>Court Date</th>
        <th>Court Address</th>
        <th>Attorney Name</th>
        <th>Attorney Address</th>
        <th>Attorney Response</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($ticket->id); ?></td>
            <td><?php echo e($ticket->name); ?></td>
            <td><?php echo e($ticket->user_email); ?></td>
            <td><?php echo e($ticket->company?->name); ?></td>
            <td><?php echo e($ticket->address); ?></td>
            <td><?php echo e($ticket->city); ?></td>
            <td><?php echo e($ticket->state); ?></td>
            <td><?php echo e($ticket->zip); ?></td>
            <td><?php echo e(\Carbon\Carbon::parse($ticket->date_issued)->toDateString()); ?></td>
            <td><?php echo e($ticket->indicator); ?></td>
            <td><?php echo e($ticket->class_commercial); ?></td>
            <td><?php echo e($ticket->road_side_inspection); ?></td>
            <td><?php echo e($ticket->vehicle_lic_no); ?></td>
            <td><?php echo e($ticket->violation?->violation); ?></td>
            <td><?php echo e($ticket->citation_no); ?></td>
            <td><?php echo e($ticket->isDverDataq()['DVER'] ? 'DVER' : ''); ?> <?php echo e($ticket->isDverDataq()['DATAQ'] ? 'DATAQ' : ''); ?> </td>
            <td><?php echo e($ticket->ticket_type); ?></td>
            <td><?php echo e($ticket->beginning_fine_amount); ?></td>
            <td><?php echo e($ticket->final_fine_amount); ?></td>
            <td><?php echo e($ticket->total_dver_points__c); ?></td>
            <td><?php echo e($ticket->total_dver_points_removed__c); ?></td>
            <td><?php echo e($ticket->original_points_value !== null ? number_format($ticket->original_points_value, 2) : ''); ?></td>
            <td><?php echo e($ticket->final_points_value !== null ? number_format($ticket->final_points_value, 2) : ''); ?></td>
            <td><?php echo e(number_format($ticket->points_saved, 2)); ?></td>
            <td><?php echo e($ticket->court_name); ?></td>
            <td><?php echo e($ticket->court_date ? \Carbon\Carbon::parse($ticket->court_date)->toDateString() : ''); ?></td>
            <td><?php echo e($ticket->court_address); ?></td>
            <td><?php echo e($ticket->attorney?->user->name); ?></td>
            <td><?php echo e($ticket->attorney?->user->address); ?></td>
            <td><?php echo e($ticket->attorney_response); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH C:\wamp64\www\PHP\trackcitations\resources\views\exports\tickets.blade.php ENDPATH**/ ?>