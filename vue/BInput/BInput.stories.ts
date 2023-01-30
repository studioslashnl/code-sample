// Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose.

import { action } from "@storybook/addon-actions";
import { Meta, Story } from "@storybook/vue3";

import Component from "./BInput.vue";

export default {
  component: Component,
  title: "Inputs/BInput",
  parameters: {
    layout: "centered",
  },
} as Meta;

//👇 We create a “template” of how args map to rendering
const Template: Story<typeof Component> = (args) => ({
  components: { Component },
  setup() {
    return { args };
  },
  data() {
    return { modelValue: args.modelValue };
  },
  template: '<Component v-bind="args" @update:modelValue="updateModelValue" v-model="modelValue" />',
  methods: { updateModelValue: action("update:modelValue") },
});

//👇 Each story then reuses that template
export const Default = Template.bind({});
Default.args = {
  placeholder: "Placeholder here",
};

//👇 Each story then reuses that template
export const WithIcon = Template.bind({});
WithIcon.args = {
  placeholder: "Address",
  icon: "house",
};

//👇 Each story then reuses that template
export const WithError = Template.bind({});
WithError.args = {
  placeholder: "Address",
  icon: "house",
  error: "Provide a valid address",
};
