<!-- // Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose. -->

<script setup lang="ts">
import type { PropType } from "vue";
import { computed, useSlots } from "vue";
import Indent from "@/partials/Indent.vue";
const props = defineProps({
  modelValue: {
    type: [String, Number] as PropType<string | number | null>,
    default: "",
  },
  placeholder: String,
  label: String,
  disabled: Boolean,
  error: String,
  type: {
    type: String as PropType<"email" | "text" | "password" | "price">,
    default: "text",
  },
  indented: Boolean,
  small: Boolean,
});
const emit = defineEmits(["update:modelValue", "change"]);
const slots = useSlots();
function change(event: Event) {
  emit("change", event);
}
const value = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});
</script>

<template>
  <div class="b-input form-group" :class="{ 'form-group--has-icon': slots.icon }">
    <div class="b-input__indent" v-if="indented">
      <indent />
    </div>

    <div class="b-input__content">
      <label class="form-group__label" v-if="slots.label || label">
        <slot name="label">{{ label }}</slot>
      </label>

      <div class="form-group__wrap">
        <div class="form-group__icon" v-if="slots.icon">
          <slot name="icon"></slot>
        </div>

        <input
          class="input"
          :type="type"
          :class="{ 'input--error': error, 'input--small': small }"
          v-model="value"
          :placeholder="placeholder"
          :disabled="disabled"
          @change="change"
        />
      </div>

      <span v-if="error" class="form-group__error">
        {{ error }}
      </span>
    </div>
  </div>
</template>

<style lang="scss" scoped src="./BInput.scss" />
