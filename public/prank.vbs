' goofy_popups.vbs
MsgBox "Hai... ada yang aneh di komputer ini?", vbInformation, "Pemberitahuan"
MsgBox "Santai, ini cuma prank kecil.", vbOKOnly, "Prank"
MsgBox "Kalau kamu marah, ini bukan saya. 🤪", vbOKOnly + vbSystemModal, "Oops"
MsgBox "Terakhir: kamu siap nonton 1 video kejutan?", vbYesNo + vbQuestion, "Surprise"
If vbYes = MsgBox("Buka browser sekarang?", vbYesNo, "Buka?") Then
  CreateObject("WScript.Shell").Run "https://www.youtube.com/watch?v=TS2I6-hkIns&pp=ygUUZGVfZHVzdDIgc2VjcmV0IHJvb20%3D"
End If
