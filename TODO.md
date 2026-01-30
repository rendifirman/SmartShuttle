# TODO: Membership Payment Integration with Paylabs

## Current Status
- Membership payment currently uses manual transfer and basic online payment
- Regular payments use Paylabs integration
- Need to align membership payment with regular payment system

## Tasks
- [ ] Add "Simulasi Bayar" button to membership payment view
- [ ] Create simulateMembershipPayment method in CustomerController
- [ ] Modify processMembershipPayment to use Paylabs like regular payments
- [ ] Ensure Paylabs integration matches regular payment system
- [ ] Test the integration

## Files to Modify
- resources/views/customer/membership_payment.blade.php
- app/Http/Controllers/CustomerController.php
