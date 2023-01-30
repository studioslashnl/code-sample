<!-- // Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose. -->

<template>
  <MenuBaseComponent :dashboardIsOpen="true">
    <div class="dashboard">
      <div class="dashboard__container">
        <div class="dashboard__row">
          <StudentDetailsComponent class="dashboard__card" />
          <SchoolDetailsComponent class="dashboard__card" />
        </div>
        <div class="dashboard__row">
          <MissionProgressComponent class="dashboard__card" />
        </div>
      </div>

      <img class="robin" src="~@module/dashboard/assets/vc_npc_atronaut_zwaaien_02--border2.png" />
    </div>
    <template #buttons>
      <button class="menu__element menu__button" @click="openPrevious">
        <span class="mdi mdi-chevron-left"></span>
        <span>Terug</span>
      </button>
    </template>
    <template #title>
      <h3>Voortgang</h3>
    </template>
  </MenuBaseComponent>
</template>

<style lang="scss" scoped></style>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import { Action, Getter } from "vuex-class";
import MenuBaseComponent from "@module/navigation/components/menu-base.vue";
import StudentDetailsComponent from "@module/dashboard/components/student-details.vue";
import SchoolDetailsComponent from "@module/dashboard/components/school-details.vue";
import MissionProgressComponent from "@module/dashboard/components/mission-progress.vue";
import { ChapterDetails } from "@/modules/chapter/chapter.types";
import { MissionDetails } from "@/modules/mission/mission.types";
import { AcademicLevel, AcademicCode } from "@/modules/user/user.types";

@Component({
  components: {
    MenuBaseComponent,
    StudentDetailsComponent,
    SchoolDetailsComponent,
    MissionProgressComponent,
  },
})
export default class DashboardView extends Vue {
  @Getter("currentChapter", { namespace: "chapter" })
  private currentChapter!: ChapterDetails;
  @Getter("currentMission", { namespace: "mission" })
  private currentMission!: MissionDetails;

  private backRouteName = "mission-selection";
  private backRouteParams = {};

  private beforeMount() {
    this.determineBack();
  }

  private determineBack() {
    if (this.currentChapter && this.currentChapter.unlocked) {
      this.backRouteName = "level-selection";
      this.backRouteParams = {
        missionAbbreviation: this.currentMission.abbreviation,
        chapterUUID: this.currentChapter.uuid,
      };
    } else if (this.currentMission) {
      this.backRouteName = "chapter-selection";
      this.backRouteParams = { missionAbbreviation: this.currentMission.abbreviation };
    }
  }

  private openPrevious() {
    this.$router.push({
      name: this.backRouteName,
      params: this.backRouteParams,
    });
  }
}
</script>

<style scoped lang="scss">
.dashboard {
  background-image: var(--course-background);
  background-position: center 0;
  background-size: cover;
  background-repeat: no-repeat;

  &__container {
    box-sizing: border-box;
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: calc(60px + 3vw) 5vw;
    display: grid;
    grid-template-columns: auto 1fr;
    grid-gap: 4 * $cs-size;
  }
  &__row {
    display: grid;
    grid-template-rows: auto 1fr;
    grid-gap: 2 * $cs-size;
  }

  &__card {
    min-width: 400px;
    max-width: 660px;
    background: #fff;
    padding: $cs-size 1.5 * $cs-size;
    margin: 0 auto;
    border-radius: $cs-border-radius;
    box-shadow: 0 0.5em 1em -0.125em rgba(10, 10, 10, 0.1), 0 0px 0 1px rgba(10, 10, 10, 0.02);
    color: #4a4a4a;
  }

  & /deep/ h1,
  & /deep/ h2,
  & /deep/ h3 {
    color: var(--course-main);
  }
  .robin {
    position: absolute;
    bottom: 1.5 * $cs-size;
    right: 2 * $cs-size;
    max-height: 36vh;
  }
  & /deep/ a {
    color: var(--missionPrimary);
  }
}
</style>
