@include('emails._header', [
    'heading'    => 'Order Confirmed!',
    'subheading' => 'Thank you for your purchase. Here\'s a summary of your order.',
])

              <!-- ORDER META -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                <tr>
                  <td style="padding:4px 0;">
                    <span style="font-size:13px;color:rgba(255,255,255,0.45);">Order #</span>
                    <span style="font-size:13px;color:#ffffff;font-weight:bold;">{{ $order->id }}</span>
                  </td>
                  <td align="right" style="padding:4px 0;">
                    <span style="font-size:13px;color:rgba(255,255,255,0.45);">Date: </span>
                    <span style="font-size:13px;color:#ffffff;">{{ $order->created_at->format('M d, Y') }}</span>
                  </td>
                </tr>
              </table>

              <!-- ORDER ITEMS -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;margin-bottom:20px;">
                <tr>
                  <td style="padding:10px 16px;border-bottom:1px solid rgba(255,255,255,0.06);">
                    <span style="font-size:11px;color:rgba(255,255,255,0.35);text-transform:uppercase;letter-spacing:1px;">Item</span>
                  </td>
                  <td align="center" style="padding:10px 16px;border-bottom:1px solid rgba(255,255,255,0.06);">
                    <span style="font-size:11px;color:rgba(255,255,255,0.35);text-transform:uppercase;letter-spacing:1px;">Qty</span>
                  </td>
                  <td align="right" style="padding:10px 16px;border-bottom:1px solid rgba(255,255,255,0.06);">
                    <span style="font-size:11px;color:rgba(255,255,255,0.35);text-transform:uppercase;letter-spacing:1px;">Price</span>
                  </td>
                </tr>
                @foreach($order->items as $item)
                <tr>
                  <td style="padding:12px 16px;border-bottom:1px solid rgba(255,255,255,0.05);">
                    <span style="font-size:14px;color:#ffffff;">{{ $item->product_name }}</span>
                    @if($item->variant_label)
                    <br><span style="font-size:12px;color:rgba(255,255,255,0.40);">{{ $item->variant_label }}</span>
                    @endif
                  </td>
                  <td align="center" style="padding:12px 16px;border-bottom:1px solid rgba(255,255,255,0.05);">
                    <span style="font-size:14px;color:rgba(255,255,255,0.70);">{{ $item->quantity }}</span>
                  </td>
                  <td align="right" style="padding:12px 16px;border-bottom:1px solid rgba(255,255,255,0.05);">
                    <span style="font-size:14px;color:#ffffff;">{{ number_format($item->price * $item->quantity, 2) }}</span>
                  </td>
                </tr>
                @endforeach
              </table>

              <!-- TOTALS -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
                @if($order->discount_amount > 0)
                <tr>
                  <td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.55);">Discount</td>
                  <td align="right" style="padding:4px 0;font-size:13px;color:#E8621A;">- {{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif
                @if($order->shipping_cost > 0)
                <tr>
                  <td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.55);">Shipping</td>
                  <td align="right" style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.70);">{{ number_format($order->shipping_cost, 2) }}</td>
                </tr>
                @endif
                @if($order->tax_amount > 0)
                <tr>
                  <td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.55);">Tax</td>
                  <td align="right" style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.70);">{{ number_format($order->tax_amount, 2) }}</td>
                </tr>
                @endif
                <tr>
                  <td style="padding:8px 0 0 0;border-top:1px solid rgba(255,255,255,0.08);font-size:15px;font-weight:bold;color:#ffffff;">Total</td>
                  <td align="right" style="padding:8px 0 0 0;border-top:1px solid rgba(255,255,255,0.08);font-size:15px;font-weight:bold;color:#E8621A;">{{ number_format($order->grand_total, 2) }} {{ $order->currency }}</td>
                </tr>
              </table>

              <!-- CTA -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center" style="padding-bottom:8px;">
                    <a href="{{ url('/orders/' . $order->id) }}"
                       style="display:inline-block;padding:14px 36px;background:linear-gradient(135deg,#E8621A,#c94e12);color:#ffffff;text-decoration:none;border-radius:8px;font-size:15px;font-weight:bold;letter-spacing:0.3px;box-shadow:0 4px 20px rgba(232,98,26,0.35);">
                      View Order Details
                    </a>
                  </td>
                </tr>
              </table>

@include('emails._footer', [
    'footerNote' => 'If you have any questions about your order, please reply to this email or contact our support team.',
])
