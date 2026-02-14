# TODO: Fix Kursi Cross Display Based on Payment Status

## Problem:
- Currently: Cross (X) appears when payment status is "process" (terpesan)
- Desired: Cross (X) should appear when payment status is "success" (terisi)

## Files to Edit:
1. app/Models/KursiTerpesan.php
   - Modify getLayoutWithStatus() method
   - Only mark seats as 'sold' (show cross) when status is 'terisi'
   - Handle 'terpesan' status differently (show as locked but without cross)

## Implementation Steps:
- [ ] 1. Modify getLayoutWithStatus() in KursiTerpesan.php to separate 'terisi' from 'terpesan'
- [ ] 2. Only set class='sold' for seats with status='terisi'
- [ ] 3. For 'terpesan' seats, show as locked/pending but without the cross

## Notes:
- Status 'terpesan' = payment in process
- Status 'terisi' = payment successful
