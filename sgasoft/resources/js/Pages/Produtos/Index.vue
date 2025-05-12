<script setup>
import { ref } from 'vue'
import { useForm, router, Link } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

const props = defineProps({
    produtos: Array,
    fornecedores: Array
})

const form = useForm({
    nome: '',
    descricao: '',
    preco: '',
    cor: '',
    referencia: '',
    fornecedor_id:'',
})

const showModal = ref(false)

const submit = () => {
    form.post('/produtos', {
        onSuccess: () => {
            form.reset()
            showModal.value = false
        }
    })
}

const deletar = (id) => {
    if (confirm('Deseja mesmo excluir este produto?')) {
        router.delete(`/produtos/${id}`)
    }
}
</script>

<template>
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
                            <NavLink :href="route('fornecedores.index')" :active="route().current('fornecedores')">
                                Fornecedores
                            </NavLink>
                        <NavLink :href="route('produtos.index')" :active="route().current('produtos')">Produtos</NavLink>
                    </div>
                </div>
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <div class="ms-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700"
                                    >
                                        {{ $page.props.auth.user.name }}
                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
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
            </div>
        </div>
    </nav>

    <div class="p-6 max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Produtos</h1>
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" @click="showModal = true">Novo Produto</button>
        </div>

        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Nome</th>
                <th class="border px-4 py-2">Fornecedor</th>
                <th class="border px-4 py-2">Preço</th>
                <th class="border px-4 py-2">Cor</th>
                <th class="border px-4 py-2">Referência</th>
                <th class="border px-4 py-2 text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="produto in produtos" :key="produto.id">
                <td class="border px-4 py-2">{{ produto.nome }}</td>
                <td class="border px-4 py-2">{{ produto.fornecedor?.nome ?? '—' }}</td>
                <td class="border px-4 py-2">R$ {{ Number(produto.preco).toFixed(2)  }}</td>
                <td class="border px-4 py-2">{{ produto.cor }}</td>
                <td class="border px-4 py-2">{{ produto.referencia }}</td>
                <td class="border px-4 py-2 text-center">
                    <button @click="deletar(produto.id)" class="text-red-600 hover:underline">Excluir</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div v-if="showModal" class="fixed z-50 inset-0 bg-black bg-opacity-50 flex items-center justify-center" @keydown.escape.window="showModal = false">
        <div class="bg-white rounded shadow-lg w-full max-w-lg p-6" role="dialog" aria-modal="true">
            <h2 class="text-xl font-semibold mb-4">Novo Produto</h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nome</label>
                    <input v-model="form.nome" type="text" class="mt-1 block w-full border border-gray-300 rounded p-2" />
                    <div v-if="form.errors.nome" class="text-red-600 text-sm mt-1">{{ form.errors.nome }}</div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Preço</label>
                    <input v-model="form.preco" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded p-2" />
                    <div v-if="form.errors.preco" class="text-red-600 text-sm mt-1">{{ form.errors.preco }}</div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Cor</label>
                    <input v-model="form.cor" type="text" class="mt-1 block w-full border border-gray-300 rounded p-2" />
                    <div v-if="form.errors.cor" class="text-red-600 text-sm mt-1">{{ form.errors.cor }}</div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Referência</label>
                    <input v-model="form.referencia" type="text" class="mt-1 block w-full border border-gray-300 rounded p-2" />
                    <div v-if="form.errors.referencia" class="text-red-600 text-sm mt-1">{{ form.errors.referencia }}</div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Fornecedor</label>
                    <select v-model="form.fornecedor_id" class="mt-1 block w-full border border-gray-300 rounded p-2">
                        <option value="">Selecione um fornecedor</option>
                        <option v-for="fornecedor in fornecedores" :key="fornecedor.id" :value="fornecedor.id">
                            {{ fornecedor.nome }}
                        </option>
                    </select>
                    <div v-if="form.errors.fornecedor_id" class="text-red-600 text-sm mt-1">{{ form.errors.fornecedor_id }}</div>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showModal = false" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</template>
