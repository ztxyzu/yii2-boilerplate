<template>
  <div class="box">
    <div class="box-header">
      <h4>{{ pageTitle }}</h4>
    </div>
    <admin-form v-if="admin"
                :submit-text="pageTitle"
                :admin="admin"
                :is-edit="true"
                @on-submit="editAdmin">
    </admin-form>
    <placeholder-form v-else></placeholder-form>
  </div>
</template>

<script>
  import AdminForm from '@admin/views/admin/_EditForm'
  import AdminService from '@admin/services/AdminService'
  import flatry from '@admin/utils/flatry'
  import PlaceholderForm from '@core/components/Placeholder/PlaceholderForm'

  export default {
    components: { PlaceholderForm, AdminForm },
    data() {
      return {
        pageTitle: '编辑管理员',
        admin: null
      }
    },
    async created() {
      const { data } = await flatry(AdminService.edit(this.$router.currentRoute.query.id))

      if (data) {
        this.admin = data
      }
    },
    methods: {
      async editAdmin(admin, success, callback) {
        const { data } = await flatry(AdminService.edit(admin.id, admin))

        if (data) {
          this.$message.success(data.message)
          success()
        }

        callback()
      }
    }
  }
</script>

