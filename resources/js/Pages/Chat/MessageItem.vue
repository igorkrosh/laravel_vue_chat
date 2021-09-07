<template>
  <h1>{{message.user.name}}: {{message.message}}</h1>
</template>

<script>
export default {
  props: ['message']
}
</script>

<style>

</style>