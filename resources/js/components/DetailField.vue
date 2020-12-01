<template>
  <div class="flex border-b border-40 -mx-6 px-6">
    <div class="w-1/4 py-4">
      <slot>
        <h4 class="font-normal text-80">
          {{ field.name }}
        </h4>
      </slot>
    </div>
    <div class="w-3/4 py-4 break-words">
      <slot name="value">
        <p class="text-90">
          Builder content
        </p>
      </slot>
    </div>
  </div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
