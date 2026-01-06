# Paylabs QRIS Payment Fix

## Current Issues
- HTTP 403 paramInvalid error when creating QRIS payment
- Mixing v4.x API headers with v2.3 endpoints
- Signature in headers instead of request body
- Missing required parameters: requestType, channelCode
- Callback URL using localhost in production

## Tasks
- [x] Update PaylabsService to use v2.3 API format
- [x] Move signature from headers to request body
- [x] Add missing required parameters (requestType, channelCode)
- [x] Update signature generation for v2.3
- [x] Update config callback URL
- [x] Test QRIS payment creation
- [ ] Configure production callback URL in .env
