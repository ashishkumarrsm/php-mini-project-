<div style="font-family:Arial,sans-serif;max-width:600px;margin:auto;">

    <h2 style="color:#2c3e50;">Thank you for your order!</h2>

    <p>Hi <?php echo h($order['User']['first_name']); ?>,</p>

    <p>
        Your order 
        <strong>#<?php echo $order['Order']['id']; ?></strong> 
        has been placed successfully.
    </p>

    <table width="100%" cellpadding="8" border="1" style="border-collapse:collapse;">
        
        <tr style="background:#2c3e50;color:#fff;">
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
        </tr>

        <?php foreach ($order['OrderItem'] as $item): ?>
            <tr>
                <td><?php echo h($item['Product']['name']); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
            </tr>
        <?php endforeach; ?>

        <!-- Total Row -->
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td>
                <strong>
                    $<?php echo number_format($order['Order']['total'], 2); ?>
                </strong>
            </td>
        </tr>

    </table>

    <p>
        We will notify you when your order ships.  
        Thank you for shopping with us!
    </p>

</div>