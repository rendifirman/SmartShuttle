# Fix Membership Database Error

## Problem
- CustomerController queries non-existent "memberships" table
- Membership data is stored in users table instead
- Error: SQLSTATE[42P01]: Undefined table: 7 ERROR: relation "memberships" does not exist

## Solution
- Update CustomerController methods to use User model instead of DB::table('memberships')
- Use MembershipPayment model for payment-related operations
- Remove references to separate 'payments' table

## Methods to Fix
- [ ] showMembershipForm()
- [ ] processMembershipRegistration()
- [ ] showMembershipPayment()
- [ ] processMembershipPayment()
- [ ] showMembershipPending()
- [ ] simulateMembershipPayment()
- [ ] cancelMembershipPayment()

## Testing
- [ ] Test membership registration flow
- [ ] Test membership payment flow
- [ ] Verify no more database errors
