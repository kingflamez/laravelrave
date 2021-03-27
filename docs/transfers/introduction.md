# Introduction

Transfers are used to send money to bank accounts.

## How transfers work

When a transfer is initiated, it comes with a status `NEW` indicating that the transfer has been queued for processing, and you would need to use the transfer ID to call the Fetch a Transfer endpoint to retrieve the updated status of the transfer.


## What happens when a transfer is completed?
When a transfer is completed we would push a notification to you via your Webhook. You can use the information we sent to you to confirm the status of the transfer.

If a transfer is already being processed and it fails during processing, we would also push a hook notification to you on your specified hook URL.

Click [here](/verification/webhook) to setup the webhook
