@include('emails._header', [
    'heading'    => 'Order Cancelled',
    'subheading' => 'Your order has been cancelled. See details below.',
])

              <!-- CANCELLATION HIGHLIGHT -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0"
                     style="background:rgba(220,38,38,0.08);border:1px solid rgba(220,38,38,0.25);border-radius:10px;margin-bottom:24px;">
                <tr>
                  <td style="padding:20px 24px;">
                    <p style="margin:0 0 6px 0;font-size:12px;color:rgba(255,255,255,0.40);text-transform:uppercase;letter-spacing:1px;">Order #</p>
                    <p style="margin:0;font-size:22px;font-weight:bold;color:#ffffff;">{{ $order->id }}</p>
                  </td>
                  <td align="right" style="padding:20px 24px;">
                    <p style="margin:0 0 6px 0;font-size:12px;color:rgba(255,255,255,0.40);text-transform:uppercase;letter-spacing:1px;">Status</p>
                    <p style="margin:0;font-size:16px;font-weight:bold;color:#dc2626;">Cancelled</p>
                  </td>
                </tr>
              </table>

              <!-- ORDER ITEMS SUMMARY -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;margin-bottom:24px;">
                @foreach($order->items as $item)
                <tr>
                  <td style="padding:12px 16px;{{ !$loop->last ? 'border-bottom:1px solid rgba(255,255,255,0.05);' : '' }}">
                    <span style="font-size:14px;color:#ffffff;">{{ $item->product_name }}</span>
                    @if($item->variant_label)
                    <span style="font-size:12px;color:rgba(255,255,255,0.40);margin-left:8px;">{{ $item->variant_label }}</span>
                    @endif
                  </td>
                  <td align="right" style="padding:12px 16px;{{ !$loop->last ? 'border-bottom:1px solid rgba(255,255,255,0.05);' : '' }}">
                    <span style="font-size:13px;color:rgba(255,255,255,0.55);">x{{ $item->quantity }}</span>
                  </td>
                </tr>
                @endforeach
              </table>

              <!-- REFUND NOTE -->
              @if(in_array($order->status, ['refunded', 'partially_refunded']) || $order->totalRefunded() > 0)
              <table width="100%" cellpadding="0" cellspacing="0" border="0"
                     style="background:rgba(22,163,74,0.08);border:1px solid rgba(22,163,74,0.25);border-radius:10px;margin-bottom:24px;">
                <tr>
                  <td style="padding:16px 20px;">
                    <p style="margin:0;font-size:14px;color:rgba(255,255,255,0.75);line-height:1.6;">
                      A refund of <strong style="color:#ffffff;">{{ number_format($order->totalRefunded(), 2) }} {{ $order->currency }}</strong>
                      has been issued and will appear on your original payment method within 5–10 business days.
                    </p>
                  </td>
                </tr>
              </table>
              @endif

              <!-- CTA -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center" style="padding-bottom:8px;">
                    <a href="{{ url('/products') }}"
                       style="display:inline-block;padding:14px 36px;background:linear-gradient(135deg,#E8621A,#c94e12);color:#ffffff;text-decoration:none;border-radius:8px;font-size:15px;font-weight:bold;letter-spacing:0.3px;box-shadow:0 4px 20px rgba(232,98,26,0.35);">
                      Continue Shopping
                    </a>
                  </td>
                </tr>
              </table>

@include('emails._footer', [
    'footerNote' => 'If you did not request this cancellation or have questions, please contact our support team immediately.',
])
