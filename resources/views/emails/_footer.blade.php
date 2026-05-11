            </td>
          </tr>

          <!-- FOOTER CARD -->
          <tr>
            <td style="background:linear-gradient(180deg,#111111 0%,#0e0e0e 100%);border:1px solid rgba(232,98,26,0.6);border-top:1px solid rgba(255,255,255,0.08);border-radius:0 0 20px 20px;padding:28px 48px 32px 48px;">
              <p style="margin:0 0 16px 0;font-size:14px;color:rgba(255,255,255,0.55);">
                Regards,<br>
                <strong style="color:#ffffff;letter-spacing:0.5px;">The Voxura Team</strong>
              </p>
              <div style="height:1px;background:rgba(255,255,255,0.06);margin-bottom:16px;"></div>
              @isset($footerNote)
              <p style="margin:0;font-size:12px;color:rgba(255,255,255,0.28);line-height:1.6;">{{ $footerNote }}</p>
              @endisset
            </td>
          </tr>

          <!-- BOTTOM GLOW + COPYRIGHT -->
          <tr>
            <td align="center" style="padding-top:32px;">
              <div style="width:200px;height:1px;background:linear-gradient(90deg,transparent,rgba(232,98,26,0.20),transparent);margin:0 auto 16px auto;"></div>
              <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.18);letter-spacing:1px;text-transform:uppercase;">
                &copy; {{ date('Y') }} Voxura. All rights reserved.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
