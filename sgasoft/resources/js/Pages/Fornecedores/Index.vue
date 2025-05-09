<script setup>
import { ref, watch } from 'vue'
import { useForm, router, Link } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

const props = defineProps({
    fornecedores: Array
})

const form = useForm({
    nome: '',
    cnpj: '',
    cep: '',
    endereco: ''
})

const submit = () => {
    form.post('/fornecedores', {
        onSuccess: () => form.reset()
    })
}

const deletar = (id) => {
    if (confirm('Deseja mesmo excluir este fornecedor?')) {
        router.delete(`/fornecedores/${id}`)
    }
}

const showingNavigationDropdown = ref(false)

watch(() => form.cep, async (cep) => {
    const cleanCep = cep.replace(/\D/g, '')
    if (cleanCep.length === 8) {
        try {
            const response = await fetch(`https://viacep.com.br/ws/${cleanCep}/json/`)
            const data = await response.json()
            if (!data.erro) {
                form.endereco = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`
            }
        } catch (e) {
            console.error('Erro ao buscar o CEP:', e)
        }
    }
})
</script>

<template>
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('dashboard')">
                            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                        </Link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
                        <NavLink :href="route('fornecedores.index')" :active="route().current('fornecedores')">Fornecedores</NavLink>
                    </div>
                </div>
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <div class="ms-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        {{ $page.props.auth.user.name }}
                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>
                <ResponsiveNavLink :href="route('fornecedores.index')" :active="route().current('fornecedores')">Fornecedores</ResponsiveNavLink>
            </div>
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>

    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Cadastro de Fornecedores</h1>
        <form @submit.prevent="submit" class="space-y-4 mb-8">
            <div>
                <label class="block text-sm font-medium">Nome</label>
                <input v-model="form.nome" type="text" class="border rounded w-full p-2" />
                <div v-if="form.errors.nome" class="text-red-600 text-sm">{{ form.errors.nome }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium">CNPJ</label>
                <input v-model="form.cnpj" type="text" class="border rounded w-full p-2" />
                <div v-if="form.errors.cnpj" class="text-red-600 text-sm">{{ form.errors.cnpj }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium">CEP</label>
                <input v-model="form.cep" type="text" class="border rounded w-full p-2" />
                <div v-if="form.errors.cep" class="text-red-600 text-sm">{{ form.errors.cep }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium">Endereço</label>
                <input v-model="form.endereco" type="text" class="border rounded w-full p-2" readonly />
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Salvar</button>
        </form>

        <table class="w-full border-collapse">
            <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Nome</th>
                <th class="p-2 text-left">CNPJ</th>
                <th class="p-2 text-left">CEP</th>
                <th class="p-2 text-left">Endereço</th>
                <th class="p-2 text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="fornecedor in fornecedores" :key="fornecedor.id" class="border-b">
                <td class="p-2">{{ fornecedor.nome }}</td>
                <td class="p-2">{{ fornecedor.cnpj }}</td>
                <td class="p-2">{{ fornecedor.cep }}</td>
                <td class="p-2">{{ fornecedor.endereco }}</td>
                <td class="p-2 space-x-2 text-center">
                    <Link :href="`/fornecedores/${fornecedor.id}/edit`" class="text-blue-600 hover:underline">Editar</Link>
                    <button @click="deletar(fornecedor.id)" class="text-red-600 hover:underline">Excluir</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
