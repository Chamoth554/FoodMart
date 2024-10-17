class ControlMeta {
	constructor( controlNode ) {
		this.controlNode = controlNode;
	}

	getImageId() {
		return this.controlNode.dataset?.imageOptimizationImageId
			? parseInt( this.controlNode.dataset?.imageOptimizationImageId, 10 )
			: null;
	}

	getAction() {
		return this.controlNode.dataset?.imageOptimizationAction || null;
	}

	getContext() {
		return this.controlNode.dataset?.imageOptimizationContext || null;
	}

	getStatus() {
		return this.controlNode.dataset?.imageOptimizationStatus || null;
	}

	canBeRestored() {
		const value = this.controlNode.dataset?.imageOptimizationCanBeRestored;

		if ( ! value ) {
			return null;
		}

		return '1' === value;
	}

	allowRetry() {
		return this.controlNode.dataset?.allowRetry || null;
	}
}

export default ControlMeta;
