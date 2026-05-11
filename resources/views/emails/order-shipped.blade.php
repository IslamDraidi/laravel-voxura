@include('emails._header', [
    'heading'    => 'Your Order Is On Its Way!',
    'subheading' => 'Great news — your order has been shipped and is heading your direction.',
])

              <!-- SHIPPING HIGHLIGHT -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0"
                     style="background:rgba(232,98,26,0.08);border:1px solid rgba(232,98,26,0.25);border-radius:10px;margin-bottom:24px;">
                <tr>
                  <td style="padding:20px 24px;">
                    <p style="margin:0 0 6px 0;font-size:12px;color:rgba(255,255,255,0.40);text-transform:uppercase;letter-spacing:1px;">Order #</p>
                    <p style="margin:0;font-size:22px;font-weight:bold;color:#E8621A;">{{ $order->id }}</p>
                  </td>
                  <td align="right" style="padding:20px 24px;">
                    <p style="margin:0 0 6px 0;font-size:12px;color:rgba(255,255,255,0.40);text-transform:uppercase;letter-spacing:1px;">Status</p>
                    <p style="margin:0;font-size:16px;font-weight:bold;color:#ffffff;">Shipped ✓</p>
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

              <!-- SHIPPING ADDRESS -->
              @if($order->shipping_address)
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
                <tr>
                  <td style="padding:0 0 8px 0;">
                    <span style="font-size:12px;color:rgba(255,255,255,0.40);text-transform:uppercase;letter-spacing:1px;">Delivering to</span>
                  </td>
                </tr>
                <tr>
                  <td style="font-size:14px;color:rgba(255,255,255,0.70);line-height:1.7;">
                    @php $addr = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true); @endphp
                    {{ $addr['name'] ?? '' }}<br>
                    {{ $addr['address'] ?? '' }}<br>
                    {{ $addr['city'] ?? '' }}{{ isset($addr['state']) ? ', ' . $addr['state'] : '' }} {{ $addr['postal_code'] ?? '' }}<br>
                    {{ $addr['country'] ?? '' }}
                  </td>
                </tr>
              </table>
              @endif

              <!-- CTA -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center" style="padding-bottom:8px;">
                    <a href="{{ url('/orders/' . $order->id . '/track') }}"
                       style="display:inline-block;padding:14px 36px;background:linear-gradient(135deg,#E8621A,#c94e12);color:#ffffff;text-decoration:none;border-radius:8px;font-size:15px;font-weight:bold;letter-spacing:0.3px;box-shadow:0 4px 20px rgba(232,98,26,0.35);">
                      Track Your Order
                    </a>
                  </td>
                </tr>
              </table>

@include('emails._footer', [
    'footerNote' => 'Estimated delivery times vary by location. If you have any concerns, feel free to contact our support team.',
])
